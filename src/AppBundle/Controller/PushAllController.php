<?php

/**
 * Description of PushAllController
 *
 * @author benjuchis
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PushAllController extends Controller {
    private $u;
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
        }else {
            return $this->render('system/add_user.html.twig', ['menu' => '', 'show_subscribe' => true, 'res' => '']);
        }

    }

    private function CheckRequest($par, $ser) {
        $this->u->setUid($par['pushalluserid']);
        $key = $this->getParameter('pushall_api_key');
        if (empty($ser['REMOTE_ADDR'])) {
            $ser['REMOTE_ADDR'] = 'xxx';
        }
        if (md5($key . $this->u->getUid() . $par['time'] . $ser['REMOTE_ADDR']) == $par['sign']) {
            $this->u->result = "Юзер добавлен" . $this->u->getUid();
            return true;
        } else {
            $this->u->result = "Ошибка контрольной суммы";
            return false;
        }
    }

}
