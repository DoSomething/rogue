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
                    <li><b>Reportback:</b> This is a term that the organization has traditionally used to define whether or not a member has completed the action or not. If a member submits proof of their action, they have reported back. If a member sends in multiple proofs of their action, this is still just one report back.</li>
                    <li><b>Post:</b> A post is created every time a user submits proof that they took action on a campaign. A user can submit multiple posts for one campaign, for example, a user signs up for for Teens for Jeans and submits 5 photos. The user has 1 signup, 5 posts, and has reported back once. We currently only have photo posts, but Rogue was built to allow for different types of media.</li>
                    <li><b>Quantity:</b> Quantity user submitted and is the total number of actions a user took (i.e. how many items collected). There is one quantity for a member for a given campaign. If on the first submission they tell us they collected 50 and then in the second submission they tell us 200, their quantity is 200, not 250. <em>Note: This may change in the future.</em></li>
                    <li><b>Why participated:</b> The why participated is the reason a user submits explaining why they took action in the campaign. A user may submit multiple posts for a campaign, but they only have one "why participated."</li>
                </ul>

                <h3>What do the different tags mean?</h3>
                <p>After selecting a status, you are able to apply tags. It's important to add tags as you're reviewing because admins can filter by tags on the Campaign Filtering page!</p>

                <h4>These are self-explanatory:</h4>
                <ul class='list'>
                    <li>Good Photo</li>
                    <li>Good Quote</li>
                    <li>Good for Sponsor</li>
                </ul>

                <h4>Tags that might need more explanation:</h4>
                    <p><h4>Good for Storytelling:</h4> A tag request from the Impact team - these are things things that show good impact and member activity. Impact team will use this tag when looking for things for the Gala or other decks.</p>

                    <p>When you tag something as "Good for Storytelling" it will show up in the #badass-members Slack channel. This was an integration that Team Bleed built on request from the Marketing and Campaigns teams! Feel free to send feature requests to Team Bleed.</p>

                    <p><h4>Hide in Gallery: <em>Hides PHOTO from the gallery, does NOT exclude the impact</em></h4><p>
                    <ul class='list'>
                        <li>The reportback accurately reflects the CTA</li>
                        <li>The impact number is realistic</li>
                    </ul>

                    <p>You may add the tag, 'Hide in Gallery' to a photo for various reasons, such as:</p>
                    <ul class='list'>
                        <li>The photo is not clear (bad photo) but you want to count the impact</li>
                        <li>The photo does not fully reflect the CTA but you want to count the impact</li>
                        <li>You want to count the impact, but the caption may include language or personal information about a user (i.e. email address) we don’t want featured in the public gallery</li>
                    </ul>

                    <p><h4>Irrelevant:</h4> Means that the photo has nothing to do with the campaign (i.e. a cat photo for Teens for Jeans)</p>

                    <p><h4>Inappropriate:</h4> Means there is inappropriate content (i.e. middle finger in the background of the image, swears in the caption)</p>

                    <p><h4>Unrealistic Quantity:</h4> Means someone puts a ridiculously high number in the quantity field (i.e. they said they created 1 million Love Letters and it’s a selfie)</p>
                    <p>You can tag as unrealistic quantity, but you'd still want to update the quantity to correct it!</p>

                    <p><h4>Test:</h4> Means it’s a DS individual who’s submitting tests. (i.e., The quote or caption says "test" or the member email is @dosomething.org)</p>

                    <p><h4>Incomplete Action:</h4> Means someone didn’t complete the action</p>

                <h3>Why are tags important?</h3>
                <p>They're important because they allow admins to search for posts that fit a certain criteria and they allow us to identify trends, i.e., a lot of people are putting in unrealistic quantities or aren't completing the action which could be a result of unclear website instructions.</p>

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
                    <li><a href="https://docs.google.com/document/d/1WVAQWk9d3G8VgZ-tOxQyWYYpM5E20C4VrUnzv1NUoHY/edit?ts=5755892b" target="_blank">Reportback Reviewing 101</a></li>
                    <li><a href="https://github.com/DoSomething/rogue/wiki" target="_blank">Rogue Technical Documentation</a></li>
                </ul>

                <p>Got questions that aren't answered here or in the documentation linked above? Please ask in the #rogue channel!</p>
            </div>
        </div>
    </div>
@stop

