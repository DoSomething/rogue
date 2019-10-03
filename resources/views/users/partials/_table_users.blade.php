<div class="container">
    <div class="wrapper">
        <div class="container__block">
            <h2 class="heading -banner">{{ $role }}</h2>

            <table class="table">
                <thead>
                    <tr class="table__header">
                        <th class="table__cell">User</th>
                        <th class="table__cell">Email</th>
                        <th class="table__cell">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @if ($user)
                            <tr class="table__row">
                                <td class="table__cell"><a href={{ "/users/" . $user->id }}>{{ $user->display_name }}</a></td>
                                <td class="table__cell">{{ $user->email_preview or ''}}</td>
                                <td class="table__cell">{{ $user->mobile_preview or ''}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
