<?php

namespace Rogue\Types;

class Cause extends Type
{
    private const ADDICTION = 'addiction';
    private const ANIMAL_WELFARE = 'animal-welfare';
    private const BODY_POSITIVITY = 'body-positivity';
    private const BULLYING = 'bullying';
    private const CRIMINAL_JUSTICE = 'criminal-justice';
    private const DISASTER_RELIEF = 'disaster-relief';
    private const EDUCATION = 'education';
    private const ENVIRONMENT = 'environment';
    private const FINANCIAL_SKILLS = 'financial-skills';
    private const GUN_VIOLENCE = 'gun-violence';
    private const HEALTHCARE = 'healthcare';
    private const HOMELESSNESS_AND_POVERTY = 'homelessness-and-poverty';
    private const IMMIGRATION = 'immigration';
    private const INCOME_INEQUALITY = 'income-inequality';
    private const LGBTQ_RIGHTS = 'lgbtq-rights';
    private const MENTAL_HEALTH = 'mental-health';
    private const VETERANS = 'veterans';
    private const PHYSICAL_HEALTH = 'physical-health';
    private const RACIAL_JUSTICE = 'racial-justice';
    private const SEXUAL_HARRASSMENT = 'sexual-harassment';
    private const VOTER_REGISTRATION = 'voter-registration';
    private const WEEK_OF_ACTION = 'week-of-action';
    private const WOMENS_RIGHTS = 'womens-rights';
    private const OTHER = 'other';

    /**
     * Returns labeled list of values.
     *
     * @return array
     */
    public static function labels()
    {
        return [
            self::ADDICTION => 'Addiction',
            self::ANIMAL_WELFARE => 'Animal Welfare',
            self::BODY_POSITIVITY => 'Body Positivity',
            self::BULLYING => 'Bullying',
            self::CRIMINAL_JUSTICE => 'Criminal Justice',
            self::DISASTER_RELIEF => 'Disaster Relief',
            self::EDUCATION => 'Education (access, affordability) ',
            self::ENVIRONMENT => 'Environment',
            self::FINANCIAL_SKILLS => 'Financial Skills',
            self::GUN_VIOLENCE => 'Gun Violence',
            self::HEALTHCARE => 'Healthcare',
            self::HOMELESSNESS_AND_POVERTY => 'Homelessness & Poverty',
            self::IMMIGRATION => 'Immigration/Refugees',
            self::INCOME_INEQUALITY => 'Income Inequality',
            self::LGBTQ_RIGHTS => 'LGBTQ+ Rights & Equality ',
            self::MENTAL_HEALTH => 'Mental Health',
            self::VETERANS => 'Military/Veterans',
            self::PHYSICAL_HEALTH => 'Physical Health',
            self::RACIAL_JUSTICE => 'Racial Justice/Racial Equity',
            self::SEXUAL_HARRASSMENT => 'Sexual Harassment & Assault',
            self::VOTER_REGISTRATION => 'Voter Registration',
            self::WEEK_OF_ACTION => 'Week of Action',
            self::WOMENS_RIGHTS => 'Women\'s Rights & Equality',
            self::OTHER => 'Other',
        ];
    }
}
