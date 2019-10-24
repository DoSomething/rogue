<nav class="navigation -white -floating">
    <a class="navigation__logo" href="/"><span>DoSomething.org</span></a>
    <div class="navigation__menu">
        @if (Auth::user())
            <ul class="navigation__primary">
                <li>
                    <a href="/campaigns">
                        <strong class="navigation__title">Campaigns</strong>
                    </a>
                </li>
                <li>
                    <a href="/users">
                        <strong class="navigation__title">Users</strong>
                    </a>
                </li>
                <li>
                    <a href="/faq">
                        <strong class="navigation__title">FAQ</strong>
                    </a>
                </li>
            </ul>
            <ul class="navigation__secondary">
                <li>
                    <a href="/logout">Log Out</a>
                </li>
            </ul>
        @endif
    </div>
</nav>
