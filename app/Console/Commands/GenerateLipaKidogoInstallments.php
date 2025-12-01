<?php

namespace App\Console\Commands;

use App\Models\LipaKidogo;
use App\Models\LipaKidogoInstallment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateLipaKidogoInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lipa-kidogo:generate-installments {--plan_id= : Specific plan ID to generate installments for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate installments for Lipa Kidogo plans that are missing them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $planId = $this->option('plan_id');

        if ($planId) {
            $plans = LipaKidogo::where('id', $planId)->get();
        } else {
            $plans = LipaKidogo::whereDoesntHave('installments')->get();
        }

        if ($plans->isEmpty()) {
            $this->info('No Lipa Kidogo plans found that need installments generated.');
            return;
        }

        $this->info("Found {$plans->count()} Lipa Kidogo plan(s) that need installments generated.");

        $bar = $this->output->createProgressBar($plans->count());
        $bar->start();

        foreach ($plans as $plan) {
            $this->generateInstallmentsForPlan($plan);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Installments generation completed successfully!');
    }

    private function generateInstallmentsForPlan(LipaKidogo $plan)
    {
        $numInstallments = $plan->num_installments;
        $installmentAmount = $plan->installment_amount;
        $currentDate = Carbon::parse($plan->start_date);

        for ($i = 1; $i <= $numInstallments; $i++) {
            // Calculate due date based on payment duration
            $dueDate = match($plan->payment_duration) {
                'daily' => $currentDate->copy()->addDays($i - 1),
                'weekly' => $currentDate->copy()->addWeeks($i - 1),
                'monthly' => $currentDate->copy()->addMonths($i - 1),
                default => $currentDate->copy()->addDays($i - 1),
            };

            LipaKidogoInstallment::create([
                'lipa_kidogo_id' => $plan->id,
                'user_id' => $plan->user_id,
                'material_id' => $plan->material_id,
                'installment_number' => $i,
                'amount' => $installmentAmount,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
        }
    }
}
