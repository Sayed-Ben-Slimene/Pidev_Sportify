<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Component\Notifier\TexterInterface;
use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Internal\ClientState;

use function PHPUnit\Framework\throwException;
use Twilio\Rest\Client;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
 
    #[Route('/', name: 'app_evenement_index', methods: ['GET', 'POST'])]
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator,Request $request): Response
    {
        $events= $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        //TODO : refresh list
        $date = new \DateTimeImmutable();
        
        foreach ($events as $event) {
            if ($event->getDateEvenement() < $date) {
                var_dump("true");
                $evenementRepository->remove($event, true);
            }
        }
        $events= $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

       

        //TODO : paginator
        $events= $paginator->paginate(
            $events,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('evenement/index.html.twig', [
            'evenements' => $events,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $fieldTime=$evenement->getDateEvenement();
            $currentTime = new \DateTime();
        
            if ($fieldTime > $currentTime) {
           
                        //upload image
                $uploadedFile = $form['img']->getData();
                if ($uploadedFile) {
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $evenement->setImg($newFilename);
                }
            
                $evenementRepository->save($evenement, true);
                $name = $evenement->getNomEvenement();




           
                    $sid    = "AC24eed5b981a034372ac4b54f0385e307"; 
                    $token  = "7e329046e8d24f41bd7e766f21545810";
                    $twilio = new Client($sid, $token);                      
                    $message = $twilio->messages 
                                      ->create("+21655544291", // to 
                                               array(  
                                                   "messagingServiceSid" => "MG0954e9ea612cfc68483b5299e1f20566",      
                                                   "body" => " Vous avez un nouveau evenement sous le nom de: " .$name
                                               ) 
                                      ); 
                     
                    print($message->sid);
                    
                    
                    
        
                return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
            }  else {
                return throw new Exception('hello');
            }
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            
            
                //upload image
                $uploadedFile = $form['img']->getData();
                if ($uploadedFile) {
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $evenement->setImg($newFilename);
                }
                $evenementRepository->save($evenement, true);
                return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
            
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/reservationEvent/{id}', name: 'reservationEvent', methods: ['GET', 'POST'])]
    public function reservationEvent(Request $request, ReservationRepository $ReservationRepository,EvenementRepository $EvenementRepository, Evenement $evenet): Response
        {      
        $ListeReservation = $ReservationRepository->findBy(array('event' => $evenet->getId()), array('event' => 'ASC'));
        return $this->render('front/EventReservation.html.twig', [
            'ListeReservation' => $ListeReservation,
            'event'=>$evenet
        ]);
    }
}
