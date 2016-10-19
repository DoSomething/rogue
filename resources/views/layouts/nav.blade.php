<nav class="navigation -white -floating">
    <a class="navigation__logo" href="/"><span>DoSomething.org</span></a>
    <div class="navigation__menu">
        @if (Auth::user())
            <ul class="navigation__primary">
                <li>
                    <a href="#">
                        <strong class="navigation__title">One Fish</strong>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <strong class="navigation__title">Two Fish</strong>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <strong class="navigation__title">Three Fish</strong>
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
