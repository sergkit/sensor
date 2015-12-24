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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class SystemController extends Controller {
    private $u;

    public function aboutAction() {
        return $this->render('system/about.html.twig', ['menu'=>'about']);
    }
    public function configAction() {
        return $this->render('system/config.html.twig', ['menu'=>'config']);
    }
    public function reportAction() {
        return $this->render('system/report.html.twig',['menu'=>'report']);
    }
    public function add_recordAction(Request $request) {
        $th = new Thtable();
        $sensor_input_form = $this->createForm(new AddData(), $th);
        $sensor_input_form->handleRequest($request);
        if ($sensor_input_form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($th);
            $em->flush();
            return new Response($th->getId());
        }
        return $this->render('system/form.html.twig', ['menu' => '', 'form' => $sensor_input_form->createView()]);
    }

    public function send_formAction(Request $request){
        function r(){
            return rand(0, 1000);
        }
        $arr=[
            'add_data[co2]'=>  r(),
            'add_data[t]'=>  r(),
            'add_data[h]'=>  r(),
            'add_data[voc]'=>  r(),
            'add_data[vocr]'=>  r(),
            'add_data[vocold]'=>  r(),
        ];
        $arr['add_data[CheckFields]']=$arr['add_data[co2]']+$arr['add_data[t]']+$arr['add_data[h]']+$arr['add_data[voc]'];
       $ser=$request->server->all();
       return new Response($this->send_post("http://{$ser['HTTP_HOST']}/add_record",$arr ));

    }
    private function send_post($url, $arr){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        $output = curl_exec($ch);
        curl_close($ch);
        return "ок";
    }

    public function add_userAction(Request $request) {
       $this->u=new Users();
       if ($request->query->count()){
            if ($this->CheckRequest($request->query->all() , $request->server->all())){
                $em = $this->getDoctrine()->getManager();
                $em->persist($this->u);
                try {
                    $em->flush();
                } catch (UniqueConstraintViolationException $e){
                    return $this->render('system/add_user.html.twig',['menu'=>'', 'show_subscribe'=>false,'res'=>" Юзер существует"]);
                } catch (\Exception $e){
                    return $this->render('system/add_user.html.twig',['menu'=>'', 'show_subscribe'=>false,'res'=>$e->getMessage()]);
                }
            }
            return $this->render('system/add_user.html.twig',['menu'=>'', 'show_subscribe'=>false,'res'=>$this->u->result]);
       }else {
           $uid='sdfgjhdfkghdkfgjk';
           $time=time();
           $key=$this->getParameter('pushall_api_key');
           $ser=$request->server->all();
            if(empty($ser['REMOTE_ADDR'])){
                $ser['REMOTE_ADDR']='xxx';
            }
           $sign=md5($key . $uid . $time . $ser['REMOTE_ADDR']);
           $url=''; //"?pushalluserid=$uid&time=$time&sign=$sign";
           return $this->render('system/add_user.html.twig',['menu'=>'', 'show_subscribe'=>true, 'url'=>$url]);
       }


    }
    public function CheckRequest($par, $ser){
        $this->u->setUid($par['pushalluserid']);
        $key=$this->getParameter('pushall_api_key');
        if(empty($ser['REMOTE_ADDR'])){
            $ser['REMOTE_ADDR']='xxx';
        }
        if(md5($key . $this->u->getUid() . $par['time'] . $ser['REMOTE_ADDR'])==$par['sign']){
            $this->u->result="Юзер добавлен" . $this->u->getUid();
            return true;
        }else{
            $this->u->result="Ошибка контрольной суммы";
            return false;
        }
    }

    public function get_statAction(){
        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();

            // The getExportQuery method returns a query that is used to retrieve
            // all the objects (lines of your csv file) you need. The iterate method
            // is used to limit the memory consumption

            $rep=$em->getRepository('AppBundle:Thtable');
            $results = $rep->findAllForRoom()->iterate();
            $handle = fopen('php://output', 'r+');

            while (false !== ($row = $results->next())) {
                // add a line in the csv file. You need to implement a toArray() method
                // to transform your object into an array
                fputcsv($handle, $row[0]->toArray());
                // used to limit the memory consumption
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
    }

}
