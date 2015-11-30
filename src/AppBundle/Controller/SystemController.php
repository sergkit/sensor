<?php

/**
 * Description of SystemController
 *
 * @author benjuchis
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Thtable;
use AppBundle\Form\AddData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SystemController extends Controller {

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
        //dump($request);
        //die();
        $sensor_input_form->handleRequest($request);

        if ($sensor_input_form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($th);
            $em->flush();
            return $this->redirectToRoute('report');
        }
        return $this->render('system/form.html.twig', ['menu' => '', 'form' => $sensor_input_form->createView()]);
    }

    public function send_formAction(){
        function r(){
            return rand(0, 1000);
        }
        $arr=[
            'add_data[co2]'=>  r(),
            'add_data[t]'=>  r(),
            'add_data[h]'=>  r(),
            'add_data[VOC]'=>  r(),
            'add_data[VOCR]'=>  r(),
            'add_data[VOCold]'=>  r(),
        ];
       return new Response($this->send_post('http://gitsen/add_record',$arr ));

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

}
