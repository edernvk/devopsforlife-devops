<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StateTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(StatusMessageSeeder::class);

        // this environment is meant to be used when mocking data
        // for reviewers on app stores or to create screenshots
        if (App::environment(['review'])) {
            $this->call(AppReviewSeeder::class);
        }

        if (App::environment(['local', 'testing'])) {
            $this->call(MockingTeamTableSeeder::class);
            $this->call(MockingUserTableSeeder::class);
            $this->call(MockingMessageTableSeeder::class);
            $this->call(MockingMagazineTableSeeder::class);
            $this->call(MockingNewsletterTableSeeder::class);
            $this->call(MockingExtensionsSeeder::class);
            $this->call(MockingBenefitsSeeder::class);
            $this->call(UnregisterCitiesSeeder::class);
            $this->call(ManagerSeeder::class);
            $this->call(ManagersCitiesSeeder::class);
        }

        if (App::environment(['staging'])) {
            $this->call(PenzeUsersSeed::class);
            $this->call(ContiProvisioningTeamsUsers::class);
            $this->call(ContiProvisioningWelcomingMessages::class);
        }

        if (App::environment(['production'])) {
            $this->call(ContiProduction::class);
            $this->call(ContiProductionExtensionsNumbersUpdated::class); // 2021-06-29
            $this->call(ContiProductionBenefitsPartnersUpdated::class); // 2021-06-29
            $this->call(ContiProductionBenefitsPartnersPatch1::class); // 2021-08-26
            $this->call(ContiProductionPaycheckAccess::class); // 2020-11-30
            $this->call(ContiProductionPaycheckAccessPatch1::class); // 2020-12-05
            $this->call(ContiProductionPaycheckAccessPatch2::class); // 2020-12-??
            $this->call(ContiProductionPaycheckAccessPatch3::class); // 2020-12-21
            $this->call(ContiProductionChristmasToken::class); // 2020-12-12
            $this->call(ContiProductionWelcomeMessage::class);
        }

        $this->call(PassportTableSeeder::class);
    }
}
