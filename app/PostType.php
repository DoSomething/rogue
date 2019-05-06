<?php

namespace Rogue;

class PostType
{
    /**
     * A text post type.
     *
     * @var string
     */
    public static $text = 'text';

    /**
     * A photo post type.
     *
     * @var string
     */
    public static $photo = 'photo';

    /**
     * A voter reg post type.
     *
     * @var string
     */
    public static $voterReg = 'voter-reg';

    /**
     * A share social post type.
     *
     * @var string
     */
    public static $shareSocial = 'share-social';

    /**
     * A phone call post type.
     *
     * @var string
     */
    public static $phoneCall = 'phone-call';

    /**
     * Returns list of all valid post types.
     *
     * @return array
     */
    public static function values()
    {
        return array_keys(self::all());
    }

    /**
     * Returns list of all valid post types.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::$text => 'Text Post',
            self::$photo => 'Photo Post',
            self::$voterReg => 'Voter Registration',
            self::$shareSocial => 'Social Share',
            self::$phoneCall => 'Phone Call (CallPower)',
        ];
    }
}
