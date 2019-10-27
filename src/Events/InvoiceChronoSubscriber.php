<?php
namespace App\Events;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class InvoiceChronoSubscriber implements EventSubscriberInterface
{
    private $security;
    private $repository;

    public function __construct(Security $security, InvoiceRepository $invoiceRepository)
    {
        $this->security = $security;
        $this->repository = $invoiceRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ["setChronoForInvoice", EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setChronoForInvoice(GetResponseForControllerResultEvent $event) {
        $invoice = $event->getControllerResult();
        $httpM = $event->getRequest()->getMethod();

        if ($invoice instanceof Invoice &&  $httpM === 'POST'){
            $nextChrono = $this->repository->findNextChrono($this->security->getUser());
            $invoice->setChrono($nextChrono);

            if(empty($invoice->getSentAt())){
                $invoice->setSentAt(new DateTime());
            }
        }
    }

}