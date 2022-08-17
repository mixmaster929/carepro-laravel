<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

    }
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function runOld()
    {
         $this->call([
             RoleTableSeeder::class,
             AdminSeeder::class,
             SettingTableSeeder::class,
             PermissionGroupsSeeder::class,
             PermissionsSeeder::class,
             NotesPermissionSeeder::class,
             AdminRoleSeeder::class,
             AdminRolePermissionSeeder::class,
             AdminUserRoleSeeder::class,
             EmploymentPermissionSeeder::class,
             AddCurrencySetting::class,
             CurrencyNameSeeder::class,
             CategoriesPermissionSeeder::class,
             OrderSettingsSeeder::class,
             OrderCommentPermissionSeeder::class,
             PaymentMethodsSeeder::class,
             SocialLoginSettingsSeeder::class,
             InvoiceCategorySeeder::class,
             InvoiceCategoryPermissionSeeder::class,
             OrderSettingCategorySeeder::class,
             JobCategoryPermissionSeeder::class,
             EmailsPermissionSeeder::class,
             SmsGatewaysSeeder::class,
             TemplatePermissionSeeder::class,
             InterviewPermissionSeeder::class,
            TestPermissionsSeeder::class,
             TestResultPermissionSeeder::class,
             BlogPermissionSeeder::class,
             TemplateSeeder::class,
             CountriesTableSeeder::class,
         ]);
    }
}
