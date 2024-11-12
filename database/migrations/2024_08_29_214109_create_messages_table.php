<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('messages');
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_support')->default(false);
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_seen')->nullable();
            $table->string('regions')->default('');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('executor_id')->nullable()->after('user_id');
        });

        // Seed messages
        $this->seedMessages();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('messages');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_seen');
            $table->dropColumn('regions');
        });
        Schema::table('projects', function (Blueprint $table) {
           $table->dropColumn('executor_id');
        });
    }

    /**
     * Seed messages
     */
    private function seedMessages()
    {
        $users = [1, 7, 10, 16];
        $support_id = 500;

        // Create a user for support if it doesn't exist
        DB::table('users')->insertOrIgnore([
            'id' => $support_id,
            'name' => 'Тех поддержка',
            'email' => 'support@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $messages = [
            'Здравствуйте! У меня возник вопрос по поводу заказа.',
            'Добрый день! Конечно, я готов помочь. Что именно вас интересует?',
            'Спасибо за быстрый ответ. Когда ожидается доставка моего заказа?',
            'Я проверил информацию по вашему заказу. Доставка запланирована на следующий вторник.',
            'Отлично, спасибо за информацию!',
            'Всегда рады помочь. Если у вас возникнут еще вопросы, обращайтесь.',
        ];

        foreach ($users as $user) {
            // Messages between users
            foreach ($users as $receiver) {
                if ($user !== $receiver) {
                    for ($i = 0; $i < 2; $i++) {
                        DB::table('messages')->insert([
                            'sender_id' => $user,
                            'receiver_id' => $receiver,
                            'content' => $messages[array_rand($messages)],
                            'created_at' => Carbon::now()->subMinutes(rand(1, 1000)),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }

            // Messages with support
            DB::table('messages')->insert([
                'sender_id' => $user,
                'receiver_id' => $support_id,
                'content' => 'У меня возникла проблема с оплатой. Можете помочь?',
                'created_at' => Carbon::now()->subMinutes(rand(1, 1000)),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('messages')->insert([
                'sender_id' => $support_id,
                'receiver_id' => $user,
                'content' => 'Конечно, я готов помочь вам с проблемой оплаты. Пожалуйста, опишите подробнее, что именно у вас не получается.',
                'is_support' => true,
                'created_at' => Carbon::now()->subMinutes(rand(1, 500)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
};
