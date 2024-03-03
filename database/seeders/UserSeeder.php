<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();

        $users = [
            [
                'email' => 'owner@gmail.com',
                'password' =>  Hash::make('12345678'),
                'name' => 'owner',
                'user_name' => 'owner',
                'role' => 'owner',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'email' => 'manager@gmail.com',
                'password' =>  Hash::make('12345678'),
                'name' => 'manager',
                'user_name' => 'manager',
                'role' => 'manager',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'email' => 'cashier@gmail.com',
                'password' =>  Hash::make('12345678'),
                'name' => 'cashier',
                'user_name' => 'cashier',
                'role' => 'cashier',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ];

        DB::table('users')->insert($users);
    }
}
