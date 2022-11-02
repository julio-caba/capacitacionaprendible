<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books(){
           $books = Book::factory(4)->create();         
            /* ruta index */
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
           ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

   /* test creado con el titulo */
 function test_can_get_one_book(){
    $book = Book::factory()->create();
    
    $response = $this->getJson(route('books.show', $book));

    $response->assertJsonFragment([
        'title' => $book->title
    ]);
 }

 /** @test */
 function can_create_books(){

    $this->postJson(route('books.store'),[])
            ->assertJsonValidationErrorFor('title');

    $this->postJson(route('books.store'),[
        'title' => 'Nuevo libro'
    ])->assertJsonFragment([
        'title' => 'Nuevo libro'
    ]);

    $this->assertDatabaseHas('books',[
        'title' => 'Nuevo libro'
    ]);
 }

 /** @test */
 function can_update_books(){
    $book = Book::factory()->create();    
    $this->patchJson(route('books.update',$book),[
        'title' => 'Libro editado'
    ])->assertJsonFragment([
        'title' => 'Libro editado'
    ]);
    
    $this->assertDatabaseHas('books',[
        'title' => 'Libro editado'
    ]);
 }

 /** @test */
 function can_delete_books(){
    $book = Book::factory()->create();    
    $this->deleteJson(route('books.destroy', $book))
        ->assertNoContent();    
    $this->assertDatabaseCount('books',0);    
 }

}
