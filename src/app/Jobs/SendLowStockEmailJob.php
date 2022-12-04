<?php

namespace App\Jobs;

use App\Mail\IngredientLowStockEmail;
use App\Repositories\IngredientRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendLowStockEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ingredients;

    private IngredientRepository $ingredientRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ingredients, IngredientRepository $ingredientRepository)
    {
        $this->ingredients = $ingredients;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->ingredients as $ingredient) {
            $email = new IngredientLowStockEmail($ingredient);

            Mail::to($ingredient->merchant->email)->send($email);

            $ingredientData = [
                'low_stock_email_sent' => 1,
            ];

            $this->ingredientRepository->update($ingredient->id, $ingredientData);
        }
    }
}
