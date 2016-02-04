<?php

/**
 * Description of PushAllController
 *
 * @author benjuchis
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PushAllController extends Controller {

    public function add_userAction(Request $request) {
        $this->u = new Users();
        if ($request->query->count()) {
            if ($this->CheckRequest($request->query->all(), $request->server->all())) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($this->u);
                try {
                    $em->flush();
                } catch (UniqueConstraintViolationException $e) {
                    return $this->render('system/add_user.html.twig', ['menu' => '', 'show_subscribe' => false, 'res' => " Юзер существует"]);
                } catch (\Exception $e) {
                    return $this->render('system/add_user.html.twig', ['menu' => '', 'show_subscribe' => false, 'res' => $e->getMessage()]);
                }
            }
            return $this->render('system/add_user.html.twig', ['menu' => '', 'show_subscribe' => false, 'res' => $this->u->result]);
        }
//        else {
//            $uid = 'sdfgjhdfkghdkfgjk';
//            $time = time();
//            $key = $this->getParameter('pushall_api_key');
//            $ser = $request->server->all();
//            if (empty($ser['REMOTE_ADDR'])) {
//                $ser['REMOTE_ADDR'] = 'xxx';
//            }
//            $sign = md5($key . $uid . $time . $ser['REMOTE_ADDR']);
//            $url = ''; //"?pushalluserid=$uid&time=$time&sign=$sign";
//            return $this->render('system/add_user.html.twig', ['menu' => '', 'show_subscribe' => true, 'url' => $url]);
//        }
    }

}
