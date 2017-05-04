<?php

use Illuminate\Database\Seeder;
use App\Repositories\FeeRepository;

class FeeSeed extends Seeder
{
    /**
     * In this seed in particular we should call directly the repository
     * so it automatically create and sync all the data related to the fee with the Gateway API
     *
     * @return void
     */
    public function run()
    {
    	(new FeeRepository())->create([
        	"slug" => "clube-iniciante",
        	"name" => "Clube Iniciante",
        	"description" => "Plano desenhado para tirar todos os obstáculos do caminho do seu primeiro clube de assinatura",
        	"billing_cycle" => 1,
        	"billing_period" => "m",
        	"price" => 60.0,
        	"price_per_subscriber" => 1.0,
        	"is_active" => 1
    	]);
    }
}
