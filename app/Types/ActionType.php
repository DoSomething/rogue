<?php

namespace Rogue\Types;

class ActionType extends Type
{
    private const HOST_EVENT = 'host-event';
    private const HAVE_CONVERSATION = 'have-a-conversation';
    private const COLLECT_SOMETHING = 'collect-something';
    private const DONATE_SOMETHING = 'donate-something';
    private const MAKE_SOMETHING = 'make-something';
    private const SHARE_SOMETHING = 'share-something';
    private const FLAG_CONTENT = 'flag-content';
    private const ATTEND_EVENT = 'attend-event';
    private const SIGN_PETITION = 'sign-petition';
    private const CONTACT_DECISIONMAKER = 'contact-decisionmaker';
    private const OTHER = 'other';

    /**
     * Returns labeled list of values.
     *
     * @return array
     */
    public static function labels()
    {
        return [
            self::HOST_EVENT => 'Host an Event',
            self::HAVE_CONVERSATION => 'Have a Conversation',
            self::COLLECT_SOMETHING => 'Collect Something',
            self::DONATE_SOMETHING => 'Donate Something',
            self::MAKE_SOMETHING => 'Make Something',
            self::SHARE_SOMETHING => 'Share Something',
            self::FLAG_CONTENT => 'Flag Content',
            self::ATTEND_EVENT => 'Attend an Event',
            self::SIGN_PETITION => 'Sign a Petition',
            self::CONTACT_DECISIONMAKER => 'Contact a Decision-Maker',
            self::OTHER => 'Other',
        ];
    }
}
