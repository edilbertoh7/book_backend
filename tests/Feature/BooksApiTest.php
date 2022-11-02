<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    /**@test */
    public function test_can_get_books()
    {
        $books = Book::factory(4)->create();
        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])
            ->assertJsonFragment([
                'title' => $books[1]->title
            ]);
    }

    /**@test */
    public function test_can_get_one_book()
    {
        $book = Book::factory()->create();
        $response = $this->getJson(route('books.show', $book->id));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /**@test */

    public function test_can_create_book()
    {
        $this->postJson(route('books.store'), [])
        ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'Book title'
        ])->assertJsonFragment([
            'title' => 'Book title'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Book title'
        ]);

    }

    /**@test */
    public function test_can_update_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update',$book), [])
        ->assertJsonValidationErrorFor('title');

         $this->patchJson(route('books.update', $book), [
            'title' => 'edited Title'
        ])
            ->assertJsonFragment([
                'title' => 'edited Title'
            ]);
        $this->assertDatabaseHas('books', [
            'title' => 'edited Title'
        ]);
    }

    /**@test */
    public function test_can_delete_book()
    {
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }




}
