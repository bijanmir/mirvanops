<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Vendor;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceComment;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo company
        $company = Company::create([
            'name' => 'Sunset Property Management',
            'slug' => 'sunset-property-management-' . Str::random(6),
            'email' => 'info@sunsetpm.com',
            'phone' => '(555) 123-4567',
            'address' => '100 Main Street',
            'city' => 'San Diego',
            'state' => 'CA',
            'zip' => '92101',
        ]);

        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@mirvanproperties.com',
            'password' => Hash::make('demo1234'),
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);

        // Create properties
        $properties = [
            [
                'name' => 'Oceanview Apartments',
                'address' => '1234 Pacific Coast Hwy',
                'city' => 'San Diego',
                'state' => 'CA',
                'zip' => '92109',
                'type' => 'residential',
                'units' => [
                    ['number' => '101', 'beds' => 1, 'baths' => 1, 'sqft' => 650, 'rent' => 1800],
                    ['number' => '102', 'beds' => 1, 'baths' => 1, 'sqft' => 650, 'rent' => 1800],
                    ['number' => '103', 'beds' => 2, 'baths' => 1, 'sqft' => 900, 'rent' => 2400],
                    ['number' => '201', 'beds' => 2, 'baths' => 2, 'sqft' => 1100, 'rent' => 2800],
                    ['number' => '202', 'beds' => 2, 'baths' => 2, 'sqft' => 1100, 'rent' => 2800],
                    ['number' => '203', 'beds' => 3, 'baths' => 2, 'sqft' => 1400, 'rent' => 3500],
                ],
            ],
            [
                'name' => 'Palm Gardens',
                'address' => '567 University Ave',
                'city' => 'San Diego',
                'state' => 'CA',
                'zip' => '92103',
                'type' => 'residential',
                'units' => [
                    ['number' => 'A1', 'beds' => 1, 'baths' => 1, 'sqft' => 600, 'rent' => 1650],
                    ['number' => 'A2', 'beds' => 1, 'baths' => 1, 'sqft' => 600, 'rent' => 1650],
                    ['number' => 'B1', 'beds' => 2, 'baths' => 1, 'sqft' => 850, 'rent' => 2200],
                    ['number' => 'B2', 'beds' => 2, 'baths' => 1, 'sqft' => 850, 'rent' => 2200],
                ],
            ],
            [
                'name' => '892 Maple Street',
                'address' => '892 Maple Street',
                'city' => 'La Jolla',
                'state' => 'CA',
                'zip' => '92037',
                'type' => 'residential',
                'units' => [
                    ['number' => 'Main', 'beds' => 4, 'baths' => 3, 'sqft' => 2400, 'rent' => 4500],
                ],
            ],
            [
                'name' => 'Downtown Lofts',
                'address' => '220 Broadway',
                'city' => 'San Diego',
                'state' => 'CA',
                'zip' => '92101',
                'type' => 'mixed',
                'units' => [
                    ['number' => '1A', 'beds' => 0, 'baths' => 1, 'sqft' => 500, 'rent' => 1400],
                    ['number' => '1B', 'beds' => 1, 'baths' => 1, 'sqft' => 700, 'rent' => 1900],
                    ['number' => '2A', 'beds' => 1, 'baths' => 1, 'sqft' => 750, 'rent' => 2000],
                    ['number' => '2B', 'beds' => 2, 'baths' => 1, 'sqft' => 950, 'rent' => 2500],
                ],
            ],
        ];

        $allUnits = [];

        foreach ($properties as $propData) {
            $property = Property::create([
                'company_id' => $company->id,
                'name' => $propData['name'],
                'address' => $propData['address'],
                'city' => $propData['city'],
                'state' => $propData['state'],
                'zip' => $propData['zip'],
                'type' => $propData['type'],
            ]);

            foreach ($propData['units'] as $unitData) {
                $unit = Unit::create([
                    'company_id' => $company->id,
                    'property_id' => $property->id,
                    'unit_number' => $unitData['number'],
                    'beds' => $unitData['beds'],
                    'baths' => $unitData['baths'],
                    'sqft' => $unitData['sqft'],
                    'market_rent' => $unitData['rent'],
                    'status' => 'vacant',
                ]);
                $allUnits[] = $unit;
            }
        }

        // Create tenants
        $tenantsData = [
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'email' => 'michael.chen@email.com', 'phone' => '(555) 234-5678'],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah.j@email.com', 'phone' => '(555) 345-6789'],
            ['first_name' => 'David', 'last_name' => 'Martinez', 'email' => 'd.martinez@email.com', 'phone' => '(555) 456-7890'],
            ['first_name' => 'Emily', 'last_name' => 'Williams', 'email' => 'emily.w@email.com', 'phone' => '(555) 567-8901'],
            ['first_name' => 'James', 'last_name' => 'Brown', 'email' => 'jbrown@email.com', 'phone' => '(555) 678-9012'],
            ['first_name' => 'Lisa', 'last_name' => 'Anderson', 'email' => 'lisa.anderson@email.com', 'phone' => '(555) 789-0123'],
            ['first_name' => 'Robert', 'last_name' => 'Taylor', 'email' => 'r.taylor@email.com', 'phone' => '(555) 890-1234'],
            ['first_name' => 'Jennifer', 'last_name' => 'Garcia', 'email' => 'jen.garcia@email.com', 'phone' => '(555) 901-2345'],
            ['first_name' => 'William', 'last_name' => 'Lee', 'email' => 'will.lee@email.com', 'phone' => '(555) 012-3456'],
            ['first_name' => 'Amanda', 'last_name' => 'Wilson', 'email' => 'a.wilson@email.com', 'phone' => '(555) 123-4567'],
        ];

        $tenants = [];
        foreach ($tenantsData as $data) {
            $tenants[] = Tenant::create([
                'company_id' => $company->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);
        }

        // Create leases (occupy 10 of 15 units = 67% occupancy)
        $leaseUnits = array_slice($allUnits, 0, 10);
        $leases = [];

        foreach ($leaseUnits as $index => $unit) {
            $tenant = $tenants[$index];
            $startDate = Carbon::now()->subMonths(rand(1, 12))->startOfMonth();
            $endDate = $startDate->copy()->addYear();

            $lease = Lease::create([
                'company_id' => $company->id,
                'unit_id' => $unit->id,
                'tenant_id' => $tenant->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'rent_amount' => $unit->market_rent,
                'deposit_amount' => $unit->market_rent,
                'status' => 'active',
            ]);

            $leases[] = $lease;

            // Mark unit as occupied
            $unit->update(['status' => 'occupied']);
        }

        // Create payments for last 6 months
        foreach ($leases as $lease) {
            for ($i = 5; $i >= 0; $i--) {
                $paymentDate = Carbon::now()->subMonths($i)->startOfMonth()->addDays(rand(0, 5));
                $period = $paymentDate->format('F Y');

                // 90% chance payment was made
                if (rand(1, 10) <= 9) {
                    $isLate = rand(1, 10) <= 2; // 20% chance of late fee

                    Payment::create([
                        'company_id' => $company->id,
                        'lease_id' => $lease->id,
                        'tenant_id' => $lease->tenant_id,
                        'amount' => $lease->rent_amount,
                        'payment_date' => $paymentDate,
                        'payment_method' => ['ach', 'check', 'zelle', 'venmo'][rand(0, 3)],
                        'period_covered' => $period,
                        'late_fee' => $isLate ? 50 : null,
                        'status' => 'completed',
                        'recorded_by' => $user->id,
                    ]);
                }
            }
        }

        // Create vendors
        $vendorsData = [
            ['name' => 'Quick Fix Plumbing', 'specialty' => 'plumbing', 'email' => 'service@quickfixplumbing.com', 'phone' => '(555) 111-2222'],
            ['name' => 'Spark Electric', 'specialty' => 'electrical', 'email' => 'info@sparkelectric.com', 'phone' => '(555) 222-3333'],
            ['name' => 'Cool Breeze HVAC', 'specialty' => 'hvac', 'email' => 'support@coolbreezehvac.com', 'phone' => '(555) 333-4444'],
            ['name' => 'Green Thumb Landscaping', 'specialty' => 'landscaping', 'email' => 'hello@greenthumb.com', 'phone' => '(555) 444-5555'],
            ['name' => 'All Around Handyman', 'specialty' => 'general', 'email' => 'jobs@allaroundhandyman.com', 'phone' => '(555) 555-6666'],
            ['name' => 'CleanPro Services', 'specialty' => 'cleaning', 'email' => 'book@cleanpro.com', 'phone' => '(555) 666-7777'],
        ];

        $vendors = [];
        foreach ($vendorsData as $data) {
            $vendors[] = Vendor::create([
                'company_id' => $company->id,
                'name' => $data['name'],
                'specialty' => $data['specialty'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);
        }

        // Create maintenance requests
        $maintenanceData = [
            ['title' => 'Leaking faucet in bathroom', 'category' => 'plumbing', 'priority' => 'medium', 'status' => 'completed', 'cost' => 150],
            ['title' => 'AC not cooling properly', 'category' => 'hvac', 'priority' => 'high', 'status' => 'in_progress', 'cost' => null],
            ['title' => 'Broken garbage disposal', 'category' => 'appliance', 'priority' => 'medium', 'status' => 'new', 'cost' => null],
            ['title' => 'Electrical outlet not working', 'category' => 'electrical', 'priority' => 'medium', 'status' => 'assigned', 'cost' => null],
            ['title' => 'Water heater making noise', 'category' => 'plumbing', 'priority' => 'low', 'status' => 'completed', 'cost' => 275],
            ['title' => 'Front door lock is sticky', 'category' => 'general', 'priority' => 'low', 'status' => 'completed', 'cost' => 75],
            ['title' => 'Smoke detector beeping', 'category' => 'general', 'priority' => 'high', 'status' => 'completed', 'cost' => 25],
            ['title' => 'Dishwasher leaking', 'category' => 'appliance', 'priority' => 'high', 'status' => 'new', 'cost' => null],
        ];

        foreach ($maintenanceData as $index => $data) {
            $lease = $leases[$index % count($leases)];
            $unit = $lease->unit;
            $vendor = $data['status'] !== 'new' ? $vendors[rand(0, count($vendors) - 1)] : null;

            $request = MaintenanceRequest::create([
                'company_id' => $company->id,
                'unit_id' => $unit->id,
                'lease_id' => $lease->id,
                'title' => $data['title'],
                'description' => 'Tenant reported: ' . $data['title'] . '. Please address as soon as possible.',
                'category' => $data['category'],
                'priority' => $data['priority'],
                'status' => $data['status'],
                'vendor_id' => $vendor?->id,
                'actual_cost' => $data['cost'],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Add comments to some requests
            if ($data['status'] !== 'new') {
                MaintenanceComment::create([
                    'maintenance_request_id' => $request->id,
                    'user_id' => $user->id,
                    'body' => 'Contacted vendor to schedule service.',
                    'created_at' => $request->created_at->addHours(2),
                ]);
            }

            if ($data['status'] === 'completed') {
                MaintenanceComment::create([
                    'maintenance_request_id' => $request->id,
                    'user_id' => $user->id,
                    'body' => 'Issue has been resolved. Tenant confirmed.',
                    'created_at' => $request->created_at->addDays(2),
                ]);
            }
        }

        // Create some activity logs
        $activities = [
            ['action' => 'created', 'model_type' => 'Property', 'description' => 'Created property: Oceanview Apartments'],
            ['action' => 'created', 'model_type' => 'Tenant', 'description' => 'Added tenant: Michael Chen'],
            ['action' => 'created', 'model_type' => 'Lease', 'description' => 'Created lease for Unit 101'],
            ['action' => 'created', 'model_type' => 'Payment', 'description' => 'Recorded payment of $1,800'],
            ['action' => 'updated', 'model_type' => 'MaintenanceRequest', 'description' => 'Updated maintenance status to completed'],
        ];

        foreach ($activities as $index => $activity) {
            ActivityLog::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'action' => $activity['action'],
                'model_type' => $activity['model_type'],
                'model_id' => 1,
                'created_at' => now()->subDays(5 - $index),
            ]);
        }

        $this->command->info('');
        $this->command->info('✅ Demo data created successfully!');
        $this->command->info('');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Email: demo@mirvanproperties.com');
        $this->command->info('   Password: demo1234');
        $this->command->info('');
        $this->command->info('📊 Created:');
        $this->command->info('   - 4 Properties');
        $this->command->info('   - 15 Units (10 occupied, 5 vacant)');
        $this->command->info('   - 10 Tenants');
        $this->command->info('   - 10 Active Leases');
        $this->command->info('   - ~50 Payments');
        $this->command->info('   - 6 Vendors');
        $this->command->info('   - 8 Maintenance Requests');
        $this->command->info('');
    }
}
