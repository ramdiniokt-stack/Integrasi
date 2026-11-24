<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['member_code' => 'AGT001', 'name' => 'Rara', 'phone' => '081234567801', 'address' => 'Karawang'],
            ['member_code' => 'AGT002', 'name' => 'Riri', 'phone' => '081234567802', 'address' => 'Karawang'],
            ['member_code' => 'AGT003', 'name' => 'Roro', 'phone' => '081234567803', 'address' => 'Cikampek'],
            ['member_code' => 'AGT004', 'name' => 'Ruru', 'phone' => '081234567804', 'address' => 'Cikampek'],
            ['member_code' => 'AGT005', 'name' => 'Rere', 'phone' => '081234567805', 'address' => 'Telukjambe'],
        ];

        foreach ($members as $member) {
            Member::updateOrCreate(
                ['member_code' => $member['member_code']],
                $member
            );
        }
    }
}