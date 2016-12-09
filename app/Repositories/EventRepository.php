<?php

namespace Rogue\Repositories;

use Rogue\Services\AWS;
use Rogue\Models\Event;
use Rogue\Models\Signup;
use Rogue\Models\Photo;
// use Rogue\Models\ReportbackLog;
// use Rogue\Models\ReportbackItem;

class EventRepository
{

    /**
     * AWS service class instance.
     *
     * @var \Rogue\Services\AWS
     */
    protected $AWS;

    /**
     * Array of properties needed for cropping and rotating.
     *
     * @var array
     */
    protected $cropProperties = ['crop_x', 'crop_y', 'crop_width', 'crop_height', 'crop_rotate'];

    /**
     * Constructor
     */
    public function __construct(AWS $aws)
    {
        $this->aws = $aws;
    }

    /**
     * Create an event.
     *
     * @param  array $data
     * @return \Rogue\Models\Event|null
     */
    public function create($data)
    {
        // AHHHH - CREATE A RELATIONSHIP BETWEEN EVENTS/SIGNUPS and EVENTS/POSTS

        if (isset($data['submission_type']) && $data['submission_type']) {
            // check the type of the event.

            // If it is not a signup, we should check the signup table to see if there is a sign up for this user already.
            if (isset($data['event_type']) && $data['event_type'] !== 'signup') {
                // @TODO - move into own repo, aside: should we be able to pass timestamp to the signup table.
                // @TODO - check assumptions about what we have in the $data array.
                $signup = Signup::where([
                    'northstar_id' => $data['northstar_id'],
                    'campaign_id' => $data['campaign_id'],
                    'campaign_run_id' => $data['campaign_run_id'],
                ])->first();

                // If there isn't, we have to create the sign up and signup event(should this be forever? eventually we could probably assume a signup. Or we can throw an error if we are trying to create Posts without a signup) and also create the item.
                if (is_null($signup)) {
                    //create signup event and signup record.
                    // Use a relationship here between event and signup.
                    $signupEvent = Event::create([
                        'northstar_id' => $data['northstar_id'],
                        'event_type' => 'signup',
                        'submission_type' => 'user',
                    ]);

                    $signup = Signup::create([
                        'event_id' => $signupEvent->id,
                        'northstar_id' => $data['northstar_id'],
                        'campaign_id' => $data['campaign_id'],
                        'campaign_run_id' => $data['campaign_run_id'],
                        'quantity' => null,
                        'quantity_pending' => $data['quantity']
                    ]);
                }

                // Hmm...could we set the event in the constructor of this class so we have event type and maybe submission type throughout the context of this runnning class.
                // @TODO - use a 'PostRepository' to handle logic like this.
                if ($data['event_type'] === 'post_photo') {
                    $postEvent = Event::create($data);

                    // $data['file_url'] = $this->aws->storeImage($data['file'], $data['campaign_id']);

                    // $cropValues = array_only($data, $this->cropProperties);

                    // if (count($cropValues) > 0) {
                    //     $editedImage = edit_image($data['file'], $cropValues);

                    //     $data['edited_file_url'] = $this->aws->storeImage($editedImage, 'edited_' . $data['campaign_id']);
                    // }

                    // $reportback->items()->create(array_only($data, ['file_id', 'file_url', 'edited_file_url', 'caption', 'status', 'reviewed', 'reviewer', 'review_source', 'source', 'remote_addr']));

                    $fileUrl = $this->aws->storeImage($data['file'], $data['campaign_id']);

                    $cropValues = array_only($data, $this->cropProperties);

                    if (count($cropValues) > 0) {
                        $editedImage = edit_image($data['file'], $cropValues);

                        $data['edited_file_url'] = $this->aws->storeImage($editedImage, 'edited_' . $data['campaign_id']);
                    }

                    $photo = Photo::create([
                        'event_id' => $postEvent->id,
                        'signup_id' => $signup->id,
                        'northstar_id' => $data['northstar_id'],
                        'file_url' => $fileUrl,
                        'edited_file_url' => $editedImage,
                        'caption' => $data['caption'],
                        'status' => isset($data['status']) ? $data['status'] : 'pending',
                    ]);
                }
            }
        }
        // For now, just return the event that was created but should think about what else should be attached to this. idea: always attach the sign up to the event, and then if a post, also attach that.


        /**
         *
         */
        //$data['event_type']
        // dd($event);

        // if ($reportback) {
        //     $reportback = $this->addItem($reportback, $data);

        //     // Record transaction in log table.
        //     $this->log($reportback, $data, 'insert');

        //     return $reportback;
        // }

        // return null;

        // return 'Create event';
    }
}
