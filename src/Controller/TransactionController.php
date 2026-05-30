<?php
namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        AccountRepository $accountRepo,
        TransactionRepository $txRepo
    ): Response {
        return $this->render('transaction/index.html.twig', [
            'accounts'     => $accountRepo->findAll(),
            'transactions' => $txRepo->findBy([], ['createdAt' => 'DESC'], 20),
        ]);
    }

    #[Route('/transfer', name: 'app_transfer', methods: ['POST'])]
    public function transfer(
        Request $request,
        AccountRepository $accountRepo,
        EntityManagerInterface $em
    ): Response {
        $fromId = $request->request->get('from_account');
        $toId   = $request->request->get('to_account');
        $amount = (float) $request->request->get('amount');

        $from = $accountRepo->find($fromId);
        $to   = $accountRepo->find($toId);

        // Validation
        if (!$from || !$to || $from === $to) {
            $this->addFlash('error', 'Invalid accounts selected.');
            return $this->redirectToRoute('app_home');
        }

        if ($amount <= 0) {
            $this->addFlash('error', 'Amount must be greater than zero.');
            return $this->redirectToRoute('app_home');
        }

        if ((float) $from->getBalance() < $amount) {
            $this->addFlash('error', 'Insufficient balance in source account.');
            return $this->redirectToRoute('app_home');
        }

        // Perform transfer
        $from->setBalance(bcsub($from->getBalance(), (string)$amount, 2));
        $to->setBalance(bcadd($to->getBalance(), (string)$amount, 2));

        $tx = new Transaction();
        $tx->setFromAccount($from)->setToAccount($to)->setAmount((string)$amount);

        $em->persist($tx);
        $em->flush();

        $this->addFlash('success', sprintf(
            'Successfully transferred %.2f %s from %s to %s.',
            $amount, $from->getCurrency(), $from->getName(), $to->getName()
        ));

        return $this->redirectToRoute('app_home');
    }
}