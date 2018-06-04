# Campaigns

A proxy endpoint for grabbing campaign content from phoenix-ashes. Currently, only supports filtering results by campaign ids.

```
GET /api/v2/campaigns
```

Note: Only campaigns with a campaign type of `campaign` are returned (e.g. `sms_game` campaigns [are filtered](https://github.com/DoSomething/LetsDoThis-iOS/issues/813#issuecomment-189545768))

### Optional Query Parameters

- **ids** _(string|csv)_
  - Specify the campaign(s) to return
  - ex: `/campaigns?ids=123,456,789`

Example Response:

```
[
    {
        "id": "1454",
        "title": "Soldier Statements ",
        "campaign_runs": {
            "current": {
                "en": {
                    "id": "1856"
                }
            },
            "past": []
        },
        "language": {
            "language_code": "en",
            "prefix": "us"
        },
        "translations": {
            "original": "en",
            "en": {
                "language_code": "en",
                "prefix": "us"
            }
        },
        "tagline": "Make a sign sharing a soldier's experience in the service.",
        "status": "active",
        "type": "campaign",
        "created_at": "1410195902",
        "updated_at": "1502734451",
        "time_commitment": 0,
        "cover_image": {
            "default": {
                "uri": "http://dev.dosomething.org:8888/sites/default/files/styles/300x300/public/images/SoldierStories_hero_square.jpg?itok=IWr6CAl1",
                "sizes": {
                    "landscape": {
                        "uri": "http://dev.dosomething.org:8888/sites/default/files/styles/1440x810/public/images/SoldierStories_hero_landscape2.jpg?itok=YtZzUJmy"
                    },
                    "square": {
                        "uri": "http://dev.dosomething.org:8888/sites/default/files/styles/300x300/public/images/SoldierStories_hero_square.jpg?itok=IWr6CAl1"
                    }
                },
                "type": "image",
                "dark_background": false
            },
            "alternate": null
        },
        "staff_pick": false,
        "competition": false,
        "facts": {
            "problem": "America’s soldiers are often isolated when they return home. They’re afraid others won’t understand or will be upset by stories of their experiences in the service.",
            "solution": "Human connection and social support promotes emotional healing. You can provide that by interviewing a soldier in your life.",
            "sources": [
                {
                    "formatted": "<p>Caplan, Paula J. (2011a). When Johnny and Jane Come Marching Home: How All of Us Can Help Veterans. Cambridge, MA: MIT Press.</p>\n"
                },
                {
                    "formatted": "<p>Watters, Ethan. (2010). The Americanization of mental illness. New York Times \nMagazine. January 8.</p>\n"
                }
            ]
        },
        "solutions": {
            "copy": {
                "raw": "Help a soldier connect by asking them about their time in the service. Work together to create a powerful statement based on your conversation.",
                "formatted": "<p>Help a soldier connect by asking them about their time in the service. Work together to create a powerful statement based on your conversation.</p>\n"
            },
            "support_copy": {
                "raw": "Cupcake ipsum dolor sit amet chocolate bar chocolate bar\r\n\r\nLemon drops chocolate bar fruitcake lollipop.",
                "formatted": "<p>Cupcake ipsum dolor sit amet chocolate bar chocolate bar</p>\n\n<p>Lemon drops chocolate bar fruitcake lollipop.</p>\n"
            }
        },
        "pre_step": {
            "header": "Ask Away!",
            "copy": {
                "raw": "Ask the soldier if you can take notes or record the interview. You'll use these stories to create a sign with a one-sentence summary of something the soldier experienced. See the action guide in Stuff You Need for examples.",
                "formatted": "<p>Ask the soldier if you can take notes or record the interview. You'll use these stories to create a sign with a one-sentence summary of something the soldier experienced. See the action guide in Stuff You Need for examples.</p>\n"
            }
        },
        "latest_news": {
            "latest_news": null
        },
        "causes": {
            "primary": null,
            "secondary": null
        },
        "action_types": {
            "primary": null,
            "secondary": null
        },
        "action_guides": [
            {
                "id": "1453",
                "title": "Questions to Avoid",
                "subtitle": null,
                "description": "These questions you shouldn't ask",
                "intro": {
                    "title": "Questions not to ask: ",
                    "copy": {
                        "raw": "Some topics and questions are tough for a soldier or veteran to talk about, so it's best to be aware of what's insensitive or inappropriate.\r\n\r\n**DON'T (under any circumstances) ask:**   \r\n*  Did you kill anyone?    \r\n*  Did any of your friends die?  \r\n*  How could you leave your family?  \r\n*  What's the worst thing that happened to you over there?  \r\n*  Do you have PTSD?   \r\n*  Do you regret serving?",
                        "formatted": "<p>Some topics and questions are tough for a soldier or veteran to talk about, so it's best to be aware of what's insensitive or inappropriate.</p>\n\n<p><strong>DON'T (under any circumstances) ask:</strong><br />\n*  Did you kill anyone?<br />\n*  Did any of your friends die?<br />\n*  How could you leave your family?<br />\n*  What's the worst thing that happened to you over there?<br />\n*  Do you have PTSD?<br />\n*  Do you regret serving?</p>\n"
                    }
                },
                "additional_text": {
                    "title": null,
                    "copy": {
                        "raw": "  ",
                        "formatted": ""
                    }
                },
                "created_at": "1410195851",
                "updated_at": "1445888544"
            }
        ],
        "attachments": [],
        "issue": null,
        "tags": [
            {
                "id": "667",
                "name": "hot"
            }
        ],
        "timing": {
            "high_season": null,
            "low_season": null
        },
        "services": {
            "mobile_commons": {
                "opt_in_path_id": null,
                "friends_opt_in_path_id": null
            },
            "mailchimp": {
                "grouping_id": null,
                "group_name": null
            }
        },
        "affiliates": {
            "partners": [
                {
                    "name": "Amex",
                    "sponsor": true,
                    "copy": null,
                    "uri": null,
                    "media": {
                        "uri": "http://dev.dosomething.org:8888/sites/default/files/styles/wmax-423px/public/partners/AXP_2C_grad.gif?itok=qS2Fz62q",
                        "type": "image"
                    }
                }
            ]
        },
        "reportback_info": {
            "copy": "Let's see a pic of your soldier or veteran with their Soldier Statement! ",
            "confirmation_message": "Nice! Thank you for reaching out to our service members.",
            "noun": "Soldiers",
            "verb": "Interviewed"
        },
        "uri": "http://dev.dosomething.org:8888/api/v1/campaigns/1454",
        "magic_link_copy": null
    }
]
```
