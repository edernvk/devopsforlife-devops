<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
	{
		$this->app->bind(\App\Repositories\Interfaces\FolderInterface::class, \App\Repositories\Eloquent\FolderEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\FileSignatureInterface::class, \App\Repositories\Eloquent\FileSignatureEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\FileInterface::class, \App\Repositories\Eloquent\FileEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\ProductNotFoundInterface::class, \App\Repositories\Eloquent\ProductNotFoundEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\ManagerInterface::class, \App\Repositories\Eloquent\ManagerEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\ProductInterface::class, \App\Repositories\Eloquent\ProductEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\NewsletterNewsInterface::class, \App\Repositories\Eloquent\NewsletterNewsEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\UserVideocastTrackedInterface::class, \App\Repositories\Eloquent\UserVideocastTrackedEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\ExtensionDivisionInterface::class, \App\Repositories\Eloquent\ExtensionDivisionEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\ExtensionAreaInterface::class, \App\Repositories\Eloquent\ExtensionAreaEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\BenefitAreaInterface::class, \App\Repositories\Eloquent\BenefitAreaEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\BenefitDivisionInterface::class, \App\Repositories\Eloquent\BenefitDivisionEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\BenefitInterface::class, \App\Repositories\Eloquent\BenefitEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\PaycheckAccessInterface::class, \App\Repositories\Eloquent\PaycheckAccessEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\ExtensionNumberInterface::class, \App\Repositories\Eloquent\ExtensionNumberEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\NewsletterInterface::class, \App\Repositories\Eloquent\NewsletterEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\VideocastInterface::class, \App\Repositories\Eloquent\VideocastEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\HealthDocsInterface::class, \App\Repositories\Eloquent\HealthDocsEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\StatusMessageInterface::class, \App\Repositories\Eloquent\StatusMessageEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\MagazineInterface::class, \App\Repositories\Eloquent\MagazineEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\RoleInterface::class, \App\Repositories\Eloquent\RoleEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\MessageInterface::class, \App\Repositories\Eloquent\MessageEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\TeamInterface::class, \App\Repositories\Eloquent\TeamEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\StateInterface::class, \App\Repositories\Eloquent\StateEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\UserInterface::class, \App\Repositories\Eloquent\UserEloquent::class);
		$this->app->bind(\App\Repositories\Interfaces\CityInterface::class, \App\Repositories\Eloquent\CityEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\GroupInterface::class, \App\Repositories\Eloquent\GroupEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\CommentInterface::class, \App\Repositories\Eloquent\CommentEloquent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
