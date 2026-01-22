<x-mail::message>
# Introduction

Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium placeat quaerat quia non a. Ipsa eaque tenetur consequatur suscipit est, quos dicta accusantium, illo nemo hic incidunt. Commodi, voluptates odit?
Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus voluptatibus molestias voluptates at soluta ipsam tenetur, beatae reiciendis deserunt corrupti magni. Beatae expedita nisi esse quisquam ad, distinctio eos assumenda.

<x-mail::button :url="''">
Button Text
</x-mail::button>

<x-mail::subcopy>
If you're having trouble clicking the "Button Text" button, copy and paste the URL below into your web browser.
</x-mail::subcopy>

<x-mail::panel>
Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nisi esse blanditiis quos facilis cumque itaque laboriosam tenetur temporibus id, tempore unde, porro quis nemo repellendus alias, maxime perspiciatis quas perferendis?
</x-mail::panel>

<x-mail::panel>
- Lorem ipsum dolor sit amet consectetur adipisicing elit
- Lorem ipsum dolor sit amet consectetur adipisicing
- Lorem ipsum dolor sit amet consectetur adipisicing
</x-mail::panel>

> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.

<x-mail::table>
| Produto | Quantidade | Preço | Preço | Preço |
|---------|------------|--------|--------|--------|
| Café    | 2          | R$ 10  | R$ 10  | R$ 10  |
| Pão     | 1          | R$ 3   | R$ 3   | R$ 3   |
| Pão     | 1          | R$ 3   | R$ 3   | R$ 3   |
| Pão     | 1          | R$ 3   | R$ 3   | R$ 3   |
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
