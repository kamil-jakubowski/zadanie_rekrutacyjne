<?php
declare(strict_types=1);

namespace App\Shared\CQRS\Command;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface CommandResourceCreatingInterface
{
    /**
     * Tak, wiem to jest antypattern, zaprzeczenie idei CQRS, że komenda nigdy nie zwraca żadnych danych. Jednak postaram się uzasadnić dlaczego w niektorych sytuacjach nie jest to dla mnie zlo wcielone
     * 1) Po pierwsze tak. Jeśli mielibyśmy doczynienia z asynchroniczną obsługą komend, albo z event sourcingiem, to takie rozwiazanie byloby rzeczywiscie problematyczne , wtedy przyjalbym generowanie guid po stronie "przed komenda"
     *
     * 2) Wiele żarliwych dyskusji jest w internecie na ten temat. Niektórzy twierdzą że to absolutnie zabronione. Niektórzy że zabronione jest zwracanie logiki biznesowej, czyli np calego dokumentu, a juz np data operacji, rewizja kolejnej wersji zasobu, czy wlasnie unikalny identyfikator to sa metadane, a wiec mozna je zwrocic.
     *
     * 3) Ja sie to staram jakos wyposrodkowac. Jesli mamy prosta aplikacyjke taka jak ta. ktora jest synchroniczna to wolę enkapsulacje generowania guid w Domenie. Odchodzą dodatkowe walidacje aby upewnić się czy user z UI nie użył dwa razy tego samego guid, zanim baza odpowie errorem o UNIQUE CONSTRAINT (guid generowane jest zawsze inne, ale mozna uzyc tego samego dwa razy)
     *
     * 4) Gdyby chodzilo o aplikacje skalowalna, ktora moze byc pozniej asynchronicznie przetwarzana kolejkowo, oczywiscie postawil bym na te swietosci i generowal guid po stronie klienta korzystajacego z interfejsu CQRS. Tutaj jest to zadanie rekrutacyjne i nie chcialem utrudniac - natomiast mysle, ze ten opis stanowi dowod, ze jestem swiadom zalet i wad i jest to rozwiazanie swiadome, a nie lamanie zasad z powodu niewiedzy :)
     *
     * Jakby co mozemy podyskutowac :) Programowanie to troche sztuka, dla mnie nie ma w niej jednego slusznego rozwiazania. :)
     *
     * @return string
     */
    public function getNewResourceUuid(): string;
    public function setNewResourceUuid(string $uuid): void;
}