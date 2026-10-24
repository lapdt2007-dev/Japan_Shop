<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewAndAuthTest extends ShopTestCase
{
    public function test_user_cannot_review_a_product_they_have_not_received(): void
    {
        $product = $this->makeProduct();
        $user    = User::factory()->create();

        $this->actingAs($user)
            ->post(route('product.review', $product->id), ['rating' => 5, 'comment' => 'Tuyệt'])
            ->assertSessionHas('error');

        $this->assertSame(0, Review::count());
    }

    public function test_buyer_can_review_once_and_resubmitting_updates_the_review(): void
    {
        $product = $this->makeProduct();
        $user    = User::factory()->create();
        $this->makeOrder($product, 1, ['user_id' => $user->id, 'order_status' => 'completed']);

        $this->actingAs($user)->post(route('product.review', $product->id), ['rating' => 5, 'comment' => 'Tốt']);
        $this->actingAs($user)->post(route('product.review', $product->id), ['rating' => 3, 'comment' => 'Cũng được']);

        $this->assertSame(1, Review::count());
        $this->assertEquals(3, Review::first()->rating);
    }

    public function test_registration_always_creates_a_customer_even_if_role_is_posted(): void
    {
        $this->post(route('register.post'), [
            'name'                  => 'Kẻ tấn công',
            'email'                 => 'x@example.com',
            'password'              => 'matkhau123',
            'password_confirmation' => 'matkhau123',
            'role'                  => 'admin',
        ])->assertRedirect('/');

        $this->assertSame('customer', User::where('email', 'x@example.com')->value('role'));
    }

    public function test_login_is_locked_after_too_many_failed_attempts(): void
    {
        User::factory()->create(['email' => 'u@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.post'), ['email' => 'u@example.com', 'password' => 'sai']);
        }

        // Lần thứ 6: dù đúng mật khẩu ('password') vẫn bị khoá tạm thời
        $this->post(route('login.post'), ['email' => 'u@example.com', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_feedback_is_persisted(): void
    {
        $this->post(route('feedback.store'), [
            'name' => 'Khách', 'email' => 'k@example.com', 'message' => 'Shop giao nhanh!',
        ])->assertSessionHas('success');

        $this->assertSame(1, DB::table('feedbacks')->count());
    }

    public function test_chatbot_endpoint_has_been_removed(): void
    {
        $this->post('/chatbot/reply', ['message' => 'hi'])->assertNotFound();
    }

    public function test_public_pages_render(): void
    {
        $product = $this->makeProduct();

        $this->get('/')->assertOk();
        $this->get(route('products.index'))->assertOk();
        $this->get(route('client.products.show', $product->slug))->assertOk();
        $this->get(route('cart.index'))->assertOk();
        $this->get(route('login'))->assertOk();
    }
}
