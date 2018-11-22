<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function run()
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://jsonplaceholder.typicode.com/todos');
        $todos = \json_decode($res->getBody()->getContents(), true);
        for ($g = 0; $g <= 30; $g++) {
            $group = DB::table('items_groups')->insertGetId([
                'min_count' => rand(3, 15)
            ]);

            for ($i = 0; $i <= rand(15, 30); $i++) {
                DB::table('items')->insert([
                    'items_group_id' => $group,
                    'name' => $todos[rand(0, count($todos) - 1)]["title"],
                    'volume' => (rand(100, 10000) / 100)
                ]);
            }
        }
    }
}
