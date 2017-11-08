@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'FAQ'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h3>What is Rogue?</h3>
                <p>Rogue is an internal tool where staff and interns review and view more information about reportbacks. It is the tool you should use when looking for content for a sponsor, decks, media, etc!</p>

                <h3>Is Rogue an internal or external term?</h3>
                <p>Rogue is an internal term - it's named for <a href="https://en.wikipedia.org/wiki/Rogue_(comics)" target="_blank">this</a> superhero!</p>

                <h3>There's a lot of terms flying around, can I get some definitions?</h3>
                <ul class='list'>
                    <li><b>Signup:</b> A signup is created when a user signs up for a campaign.</li>
                    <li><b>Reportback:</b> This is a term that the organization has traditionally used to define whether or not a member has completed the action or not once. If a member submits proof of their action, they have reported back. If a member sends in multiple proofs of their action, this is still just one report back.</li>
                    <li><b>Post:</b> A post is created every time a user submits proof that they took action on a campaign. A user can submit multiple posts for one campaign, for example, a user signs up for for Teens for Jeans and submits 5 photos. The user has 1 signup, 5 posts, and has reported back once. We currently only have photo posts, but Rogue was built to allow for different types of media.</li>
                    <li><b>Quantity:</b> Quantity user submitted and is the total number of actions a user took (i.e. how many items collected). There is one quantity for a member for a given campaign. If on the first submission they tell us they collected 50 and then in the second submission they tell us 200, their quantity is 200, not 250. <em>Note: This may change in the future.</em></li>
                    <li><b>Why participated:</b> The why participated is the reason a user submits explaining why they took action in the campaign. A user may submit multiple posts for a campaign, but they only have one "why participated."</li>
                </ul>

                <h3>Can I...</h3>
                <ul class="list">
                    <li>Upload a photo for a member?</li>
                    <li>Change the quantity for a member?</li>
                    <li>Search for a member?</li>
                    <li>Search posts by tag? By status? Both?</li>
                    <li>...and so much more!</li>
                </ul>
                <p>Yes! You can do all of these things! To learn how to do any of the things above or other fun things, refer to the <a href="https://docs.google.com/document/u/1/d/1wpwwSWmYBIXwt5RKVCJkBZIMDXyZRza22527CW6eTdY/edit#heading=h.hg3m91ssxcr1" target="_blank">Rogue Admin 101 guide!</a></p>

                <h3>Documentation</h3>
                <ul class='list'>
                    <li><a href="https://docs.google.com/document/u/1/d/1wpwwSWmYBIXwt5RKVCJkBZIMDXyZRza22527CW6eTdY/edit#heading=h.hg3m91ssxcr1" target="_blank">Rogue Admin 101</a></li>
                    <li><a href="https://github.com/DoSomething/rogue/wiki" target="_blank">Rogue Technical Documentation</a></li>
                </ul>

                <p>Got questions that aren't answered here or in the documentation linked above? Please ask in the #rogue channel!</p>
            </div>
        </div>
    </div>
@stop

