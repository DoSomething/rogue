<p>Your export of signups for {{ $campaign['title'] }} is ready!</p>
<p>The export file is attached to this email.</p>
<p>
    <strong>Export Details:</strong><br />
    Campaign Title: {{ $campaign['title'] }}<br />
    Campaign ID: {{ $campaign['id'] }}<br />
    Campaign Run ID: {{ $campaign['campaign_runs']['current']['en-global']['id'] }}
</p>
