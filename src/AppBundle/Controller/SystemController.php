<?php

/**
 * Description of SystemController
 *
 * @author benjuchis
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Thtable;
use AppBundle\Entity\Users;
use AppBundle\Form\AddData;
use AppBundle\Form\setForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class SystemController extends Controller {



    public function aboutAction(Request $request) {
        return $this->render('system/about.html.twig', ['menu' => 'about']);
    }

    public function configAction() {
        return $this->render('system/config.html.twig', ['menu' => 'config']);
    }

    public function currentAction(Request $request) {
        $em = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('AppBundle:Thtable');
        /* @var $rep ThtableRepository */
        $stat = $rep->getAllStat();
        dump($stat);
        return $this->render('system/current.html.twig', ['menu' => '', 'cur'=>$stat]);
    }

    public function reportAction(Request $request) {
        $interval = $this->getInterval($request);
        $room = $this->getRoom($request);
        $file = $this->getFile($request);
        $ff = $this->make_file($room, $interval, $file);

        $container = $this->container;
        $em = $container->get('doctrine')->getManager();
        $rep = $em->getRepository('AppBundle:Rooms');
        $rooms = $rep->findAll();

        $set_input_form = $this->createForm(new setForm(), ['rooms' => $rep->PrepareRooms($rooms), 'interval' => $interval, 'room' => $room]);
        return $this->render('system/report.html.twig', ['menu' => 'report', 'ff' => $ff, 'setform' => $set_input_form->createView()]);
    }

    /**
     * Добавление новой записи с датчиков  в базу данных
     * Проверка выхода показаний за границы допустимого
     * генерация пуш сообщений при необходимости
     * @param Request $request
     * @return Response
     */
    public function add_recordAction(Request $request) {
        $th = new Thtable();
        $sensor_input_form = $this->createForm(new AddData(), $th);
        $sensor_input_form->handleRequest($request);
        $this->log($request->request,
                    $request->server->get("DOCUMENT_ROOT"). $this->getParameter('document_root').  "/files/log.log");
        if ($sensor_input_form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($th);
            $em->flush();
            return new Response($this->checkEvents($th));
        }
        return $this->render('system/form.html.twig', ['menu' => '', 'form' => $sensor_input_form->createView()]);
    }

    /**
     * Удаление старых файлов отчетов
     * @param Request $request
     * @return Response
     */
    public function remove_old_filesAction(Request $request) {
        $dir = $request->server->get("DOCUMENT_ROOT"). $this->getParameter('document_root').  "/files";  //читаем эту директорию
        $todel = 300; // время на удаление
        try {
            if ($OpenDir = opendir($dir)) {
                while (($file = readdir($OpenDir)) !== false) {
                    if ($file != "." && $file != "..") {
                        $dtime = intval(time() - filemtime("{$dir}/{$file}"));
                        if ($dtime >= $todel)
                            unlink("{$dir}/{$file}");
                    }
                }
                closedir($OpenDir);
                $mess = "Ok";
            }
        } catch (Exception $e) {
            $mess = $e->getMessage();
        }
        return new Response($mess);
    }

    private function checkEvents(Thtable $th) {
        $id = $th->getId();
        $room = $th->getRoom();
        $em = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('AppBundle:Thtable');
        /* @var $rep ThtableRepository */
        $stat = $rep->getLastStat($room);
        $events = $this->prepareEvents($stat, $room);
        return $id . $events;
    }

    private function prepareEvents($stat, $room) {
        $em = $this->container->get('doctrine')->getManager();
        /* @var $ev AppBundle\Entity\EventsRepository */
        $ev = $em->getRepository('AppBundle:Events');
        $ev->setParams($this->getParameter('pushall_chanel_id'), $this->getParameter('pushall_api_key'));
        return $ev->checkEvents($stat, $room);
    }

    public function send_formAction(Request $request) {

        function r() {
            return rand(0, 1000);
        }

        $arr = [
            'add_data[room]' => '2',
            'add_data[co2]' => r(),
            'add_data[t]' => r(),
            'add_data[h]' => r(),
            'add_data[voc]' => r(),
            'add_data[vocr]' => r(),
            'add_data[vocold]' => r(),
        ];
        $arr['add_data[CheckFields]'] = $arr['add_data[co2]'] + $arr['add_data[t]'] + $arr['add_data[h]'] + $arr['add_data[voc]'];
        $ser = $request->server->all();
        return new Response($this->send_post("http://{$ser['HTTP_HOST']}/add_record", $arr));
    }

    private function send_post($url, $arr) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        $output = curl_exec($ch);
        curl_close($ch);
        return "ок";
    }

    public function upd_repAction(Request $request) {
        $interval = $this->getInterval($request);
        $room = $this->getRoom($request);
        $file = $this->getFile($request);
        $ff = $this->make_file($room, $interval, $file);
        return $this->render('system/dygrscr.html.twig', ['ff' => $ff]);
    }

    private function make_file($room, $interval, $filename) {
        $container = $this->container;
        $em = $container->get('doctrine')->getManager();
        $rep = $em->getRepository('AppBundle:Thtable');
        $results = $rep->findAllForRoomLastDay($room, $interval)->iterate();
        $path_parts = pathinfo($filename, PATHINFO_BASENAME);
        $handle = fopen($filename, 'w');
        $cnt = 0;
        while (false !== ($row = $results->next())) {
            fputcsv($handle, $row[0]->toArray());
            $em->detach($row[0]);
            $cnt++;
        }
        fclose($handle);
        return "files/$path_parts";
    }

    private function getRoom(Request $request) {
        $room = $request->query->get("set_form");
        return (empty($room['room']) ? 1 : $room['room']);
    }

    private function getInterval(Request $request) {
        $interval = $request->query->get("set_form");
        return (empty($interval['time_period']) ? "7D" : $interval['time_period']);
    }

    private function getFile(Request $request) {
        return tempnam($request->server->get("DOCUMENT_ROOT"). $this->getParameter('document_root'). "/files", "CSV"). ".csv";
    }

    private function log($arr, $file){
       $handle = fopen($file, 'a+');
        fwrite($handle, print_r($arr, true). "/n");
        fclose($handle);

    }

}
