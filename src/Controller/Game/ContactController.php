<?php

namespace App\Controller\Game;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = $contactForm->getData();
            // dump($contact);
            // exit;
            
            $message = (new \Swift_Message('Nouveau Contact'))
                    ->setFrom($contact['email'])
                    ->setTo("MissLibellule19@gmail.com")
                    ->setBody(
                        $this->renderView(
                            'email/contact.html.twig', compact('contact')
                        ),
                        'text/html'
                    );

            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('home');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
    /**
     * @Route("/contact/template-preview", name="contact_template")
     */
    public function contactTemplatePreview()
    {
        $contact = [
        "nom" => "Marie",
        "email" => "marie.geneste.formation@gmail.com",
        "message" => "test message" ];

        return $this->render('email/contact.html.twig', [
            'contact' => $contact
        ]);
    }
}
