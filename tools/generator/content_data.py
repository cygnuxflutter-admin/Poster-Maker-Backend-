"""
Expanded content matrix for generating 2,000+ unique, authentic Postermaker push notifications.
Mapped to live database categories in u126066279_poster_db.
"""

CATEGORY_SPECS = [
    {
        "id": "creative_ideas",
        "name": "Creative Poster Ideas",
        "cat_id": 76,  # art & design
        "target_screen": "category_detail",
        "preferred_slot": "13:00",
        "count": 290
    },
    {
        "id": "birthday_anniversary",
        "name": "Birthday & Anniversary Inspiration",
        "cat_id": 47,  # Birthday
        "target_screen": "category_detail",
        "preferred_slot": "09:00",
        "count": 290
    },
    {
        "id": "festival_seasonal",
        "name": "Festival & Seasonal Poster Ideas",
        "cat_id": 55,  # Festival
        "target_screen": "category_detail",
        "preferred_slot": "17:00",
        "count": 290
    },
    {
        "id": "business_promotions",
        "name": "Business & Promotional Poster Ideas",
        "cat_id": 11,  # Business & marketing
        "target_screen": "category_detail",
        "preferred_slot": "09:00",
        "count": 290
    },
    {
        "id": "social_media",
        "name": "Social Media Design Inspiration",
        "cat_id": 34,  # Social Ad
        "target_screen": "category_detail",
        "preferred_slot": "20:00",
        "count": 285
    },
    {
        "id": "motivational",
        "name": "Motivational Creative Prompts",
        "cat_id": 79,  # Motivation
        "target_screen": "category_detail",
        "preferred_slot": "09:00",
        "count": 285
    },
    {
        "id": "feature_highlights",
        "name": "Poster-Making Features & Tips",
        "cat_id": 76,  # art & design
        "target_screen": "category_detail",
        "preferred_slot": "17:00",
        "count": 270
    }
]

# Rich data banks
CREATIVE_IDEAS = [
    ("Color Palette Magic", "Combine deep navy with gold accents for a premium, trustworthy look."),
    ("Minimalist Layouts", "Let generous empty space around your headline speak louder than cluttered graphics."),
    ("Bold Typography", "Pair an ultra-bold serif headline with a clean geometric subtitle for instant punch."),
    ("Visual Depth", "Add soft drop shadows beneath your focal elements to make text float naturally."),
    ("Retro Poster Aesthetic", "Embrace grainy paper textures and warm retro tones for an authentic vintage vibe."),
    ("Geometric Borders", "Frame your main photograph with thin circular or diamond line art."),
    ("Duotone Color Washes", "Apply contrasting two-color overlays to background photos for an editorial feel."),
    ("Negative Space Mastery", "Leave breathing room on your canvas so your core message commands attention."),
    ("Asymmetric Balance", "Position your headline off-center and balance it with a sharp, bold visual element."),
    ("Monochrome Chic", "Black and white photography paired with a single vivid accent color never fails to impress."),
    ("Magazine Cover Vibe", "Place your subject photo in front of large backdrop typography for celebrity style."),
    ("Gradient Backgrounds", "Subtle pastel radial gradients bring warmth and modern charm to lifestyle posters."),
    ("Headline First", "Ensure your most important announcement is the first thing eyes land on within 2 seconds."),
    ("Layer Hierarchy", "Use three distinct layer levels: background texture, visual subject, and crisp text."),
    ("Vintage Stamp Elements", "Add badge badges and decorative corner stamps to craft a handmade artisan look."),
    ("Neon Glow Accents", "Dark mode flyers with vibrant neon highlights create unbeatable nightlife excitement."),
    ("Abstract Backgrounds", "Fluid organic shapes behind product shots add creative energy and movement."),
    ("Photo Grid Collages", "Organize multiple snapshots into a symmetrical grid for clean memory boards."),
    ("Warm Autumn Hues", "Earthy terracotta, mustard, and forest green tones create cozy seasonal warmth."),
    ("Pastel Elegance", "Soft lavender, sage, and cream tones deliver a calming, upscale aesthetic."),
    ("Typographic Contrast", "Never mix two similar fonts; contrast a thick heavy header with a delicate body font."),
    ("Framed Text Boxes", "Enclose key offers inside a thin semi-transparent box to maintain legibility over photos."),
    ("Editorial Spacing", "Increase character letter-spacing on uppercase subheaders for an upscale boutique feel."),
    ("Focal Point Discipline", "Every poster needs one hero element; avoid competing graphics fighting for attention."),
    ("Circular Cutouts", "Present speaker or team headshots in clean circular badges with subtle borders.")
]

BIRTHDAY_ANNIVERSARY = [
    ("Milestone 30th Birthday", "Design a vibrant golden confetti poster to celebrate three unforgettable decades.", 47),
    ("Golden 50th Wedding Anniversary", "Celebrate fifty years of enduring love with a bespoke royal champagne anniversary flyer.", 18),
    ("Best Friend's Birthday Tribute", "Assemble your favorite candid memories into a custom photo collage they will cherish forever.", 47),
    ("First Birthday Keepsake", "Craft an adorable pastel milestone banner recording baby's favorite milestones and smile.", 47),
    ("Parents' Silver Jubilee", "Honor 25 years of togetherness with an elegant silver-embossed digital announcement card.", 18),
    ("Colleague Birthday Shoutout", "Send warm wishes and cheer with a clean, professional birthday poster for the team Slack or board.", 47),
    ("Grandparents' 80th Celebration", "Honor a lifetime of wisdom and love with a timeless family tribute banner.", 47),
    ("18th Birthday Bash Invitation", "Get the party started with a high-energy neon flyer that sets the mood for the night.", 47),
    ("Romantic 1st Year Anniversary", "Celebrate year one of marriage with a heartfelt poster capturing your wedding vows.", 18),
    ("Sweet 16 Birthday Party", "Sparkling glitter and chic modern fonts make this birthday invitation truly unforgettable.", 47),
    ("Surprise Birthday Party Flyer", "Keep the secret exciting with bold mystery invitation flyers for friends and family.", 47),
    ("Decade of Love Anniversary", "Ten years of memories: create a timeline poster highlighting your biggest relationship moments.", 18),
    ("Sister's Special Day", "Show your sister how much she means with a customized floral birthday greeting flyer.", 47),
    ("Brother's Birthday Card", "Design a bold, sporty greeting banner packed with energy and laughter for your brother.", 47),
    ("Mother's Birthday Tribute", "Express your deepest gratitude with a heartfelt, warm poster honoring mom on her special day.", 47),
    ("Father's Milestone Birthday", "Celebrate dad's strength and humor with an elegant, distinguished birthday card design.", 47),
    ("Pet Birthday Celebration", "Celebrate your furry friend with a cute, playful paw-print poster template.", 47),
    ("40th Birthday Celebration", "Fabulous at forty: announce the big celebration with chic, modern party poster templates.", 47),
    ("Diamond 60th Anniversary", "A celebration of enduring devotion: create an heirloom digital poster for the whole family.", 18),
    ("Kids Birthday Adventure Theme", "Pirates, superheroes, or jungle safari: design an exciting themed invitation in minutes.", 47),
    ("Retro 90s Birthday Bash", "Bring back retro nostalgia with fun cassette tapes, pixel art, and neon font styles.", 47),
    ("Backyard BBQ Birthday", "Casual grill vibes and rustic kraft paper templates for a relaxed weekend birthday gathering.", 47),
    ("Elegant Dinner Party Invite", "Set a sophisticated tone with minimalist typography and monochrome tableware accents.", 47),
    ("Twin Birthday Celebration", "Double the joy! Create a balanced split-screen poster celebrating both personalities.", 47),
    ("Virtual Birthday Card", "Gather team wishes from across the globe into a single beautiful collective celebration flyer.", 47)
]

FESTIVAL_SEASONAL = [
    ("Diwali Festival of Lights", "Illuminate your feed with radiant diya lamps and sparkling golden Diwali greeting banners.", 55),
    ("Happy New Year Countdown", "Welcome new beginnings with dazzling fireworks and modern calendar announcement posters.", 16),
    ("Christmas Joy & Peace", "Spread holiday warmth with evergreen pine, cozy snowflakes, and elegant festive cards.", 60),
    ("Holi Colors of Joy", "Celebrate vibrant colors and joyful friendship with splashy, energetic Holi posters.", 29),
    ("Ganesh Chaturthi Blessings", "Invoke prosperity and wisdom with artistic Lord Ganesha greeting templates.", 58),
    ("Navratri Dandiya Nights", "Dance to traditional beats! Design colorful garba and dandiya night event posters.", 57),
    ("Eid Mubarak Greetings", "Share heartfelt blessings and peace with crescent moon and lantern festive greeting cards.", 56),
    ("Independence Day Pride", "Honor the tricolor with patriotic banners celebrating freedom, unity, and heritage.", 86),
    ("Republic Day Salutation", "Celebrate constitutional pride and sovereignty with inspiring national tribute flyers.", 65),
    ("Makar Sankranti & Pongal", "Kites soaring in clear blue skies: design joyful harvest festival greeting cards.", 63),
    ("Raksha Bandhan Bond", "Celebrate the precious bond of siblings with touching digital greeting cards.", 53),
    ("Valentine's Day Romance", "Design sweet love notes and couples photo flyers adorned with delicate roses.", 26),
    ("Monsoon Season Refresh", "Bring rainy season freshness to your promotional flyers with soothing water droplet art.", 52),
    ("Summer Beach Fiesta", "Tropical palms, cool turquoise waters, and bright sunshine poster templates.", 12),
    ("Autumn Harvest Vibe", "Warm amber leaves and cozy seasonal tones for autumn craft fairs and bakery menus.", 59),
    ("Thanksgiving Gratitude", "Gather around gratitude with warm rustic templates for family meals and harvest sales.", 61),
    ("Halloween Costume Party", "Spooky silhouettes, glowing pumpkins, and haunted house flyer templates for fright night.", 54),
    ("Black Friday Mega Savings", "High-contrast bold neon banners built to drive shopping excitement and store visits.", 62),
    ("Cyber Monday Digital Deals", "Futuristic tech styling and countdown badges for your e-commerce promotion flyers.", 64),
    ("Earth Day Eco Campaign", "Inspire environmental action with clean botanical illustrations and green themes.", 76),
    ("International Yoga Day", "Promote inner peace and wellness with serene sunrise yoga workshop posters.", 24),
    ("Teachers' Day Tribute", "Thank the educators who shaped your journey with an inspiring blackboard tribute card.", 73),
    ("Women's Day Empowerment", "Honor strength, courage, and leadership with bold, inspiring purple celebration banners.", 79),
    ("Children's Day Smiles", "Playful doodles, bright primary colors, and cheerful balloons for fun school events.", 76),
    ("Doctor & Healthcare Day", "Express gratitude to healthcare heroes with clean, dignified appreciation posters.", 43)
]

BUSINESS_PROMOTIONS = [
    ("Weekend Flash Sale", "Put your offer front and center with eye-catching badges and bold discount lettering.", 10),
    ("New Product Launch", "Unveil your latest collection with clean studio lighting and minimalist aesthetic cards.", 11),
    ("Grand Opening Flyer", "Welcome the neighborhood with an inviting opening banner spotlighting your location.", 44),
    ("Cafe & Bakery Menu Special", "Highlight today's artisanal treats with appetizing photography and clear pricing.", 19),
    ("Fitness Studio Membership", "Inspire active lifestyles with high-energy workout shots and class schedule cards.", 24),
    ("Beauty & Salon Pamper Package", "Showcase luxury spa and salon treatments with soothing pastel elegance flyers.", 23),
    ("Real Estate Featured Property", "Showcase stunning architectural views with multi-photo grid listing templates.", 42),
    ("Job Hiring Announcement", "Attract top talent with modern, professional career opportunity posters.", 31),
    ("Online Webinar & Workshop", "Build authority and drive registrations with clean speaker profile banners.", 73),
    ("Jewellery Special Collection", "Let your gold and diamond craftsmanship sparkle on deep velvet black backdrops.", 33),
    ("Car Care & Detailing Service", "Drive bookings with sharp before-and-after shine flyers for auto workshops.", 28),
    ("Boutique Fashion Clearance", "Create urge to shop with stylish editorial lookbook sale banners.", 10),
    ("Home Decor & Furniture Sale", "Inspire cozy living with tasteful interior styling and discount badge templates.", 11),
    ("Electronics & Gadget Deals", "Sleek metallic textures and bright spec callouts for modern tech promos.", 40),
    ("Travel & Tour Package", "Ignite wanderlust with scenic mountain vistas and clear itinerary highlight cards.", 22),
    ("Restaurant Dining Deal", "Make mouths water with high-resolution food photography and combo meal posters.", 19),
    ("Professional Law & Advisory", "Establish trust and authority with refined navy typography and consultation flyers.", 83),
    ("Photography Mini-Sessions", "Book up your weekend shoots with beautiful sample portraits and package details.", 84),
    ("Customer Appreciation Day", "Reward your community with heartfelt thank you cards and VIP preview passes.", 48),
    ("Limited-Seat Masterclass", "Drive urgency with bold countdown timers and prominent enrollment buttons.", 73),
    ("Seasonal Clearance Banner", "Clear out inventory with unmistakable red-and-white markdown flyer presets.", 10),
    ("Local Market Pop-Up Stall", "Draw foot traffic to your weekend artisan booth with charming kraft paper flyers.", 11),
    ("Pet Grooming & Care Specials", "Cheer pet parents with adorable puppy portraits and clean service packages.", 23),
    ("Mobile App Launch Teaser", "Showcase sleek app mockups with glowing phone frames and download links.", 45),
    ("Co-Working Space Day Pass", "Attract remote professionals with clean desks, high-speed wifi, and trial passes.", 11)
]

SOCIAL_MEDIA_INSPIRATION = [
    ("9:16 Instagram Story Canvas", "Fill full smartphone screens with immersive vertical design layouts.", 34),
    ("Engaging WhatsApp Status", "Keep contacts engaged daily with crisp, easy-to-read announcement cards.", 34),
    ("Customer Testimonial Spotlight", "Turn genuine client reviews into authoritative, shareable social proof graphics.", 34),
    ("Daily Thought Graphic", "Transform inspiring reflections into aesthetic typography cards people love saving.", 34),
    ("Saveable Carousel Cover", "Hook curious scrollers with provocative headline questions and clean numbering.", 34),
    ("Behind the Scenes Teaser", "Share candid workspace glimpses framed with polaroid style borders.", 34),
    ("This or That Poll Graphic", "Drive comments and story interactions with split-screen visual comparison cards.", 34),
    ("Weekly Roundup Flyer", "Condense your week's highlights and wins into a neat, digestible summary board.", 34),
    ("Mini Infographic Template", "Break down complex advice into 3 clean bullet points with custom vector icons.", 34),
    ("Live Q&A Announcement", "Boost attendance for your livestream with clear time badges and guest tags.", 34),
    ("Product Showcase Story", "Spotlight product details with elegant zoom circles and callout arrows.", 34),
    ("Giveaway & Contest Flyer", "Generate viral excitement with glowing gift box illustrations and easy entry rules.", 50),
    ("Weekly Motivation Reel Cover", "Make your video thumbnails cohesive with standardized brand font overlays.", 34),
    ("Expert Tip of the Day", "Establish niche leadership by sharing bite-sized wisdom in punchy bold layouts.", 34),
    ("Podcast Episode Release", "Highlight audio waves, episode titles, and guest portraits on chic modern covers.", 34),
    ("Community Milestone Celebration", "Celebrate hitting 10k followers with confetti and heartfelt gratitude posters.", 48),
    ("Before vs After Proof", "Demonstrate real client transformations side by side for undeniable credibility.", 34),
    ("Top 5 Tools Checklist", "Create bookmarks with clean numbered list templates followers will screenshot.", 34),
    ("Seasonal Mood Board", "Curate color inspiration and aesthetic textures into cohesive brand collages.", 76),
    ("Event Countdown Banner", "Build anticipation for tomorrow's big event with bold countdown numerals.", 34)
]

MOTIVATIONAL_PROMPTS = [
    ("Start Where You Are", "Use whatever tools you have right now. Great things have humble beginnings.", 79),
    ("Consistency Over Perfection", "Small daily progress produces extraordinary lifetime results. Keep creating.", 79),
    ("Your Vision Matters", "Nobody else sees the world through your unique creative lens. Bring it to life.", 79),
    ("Embrace the Blank Canvas", "Every masterpiece started as an empty space. Take the first bold stroke today.", 79),
    ("Turn Thoughts into Art", "Do not let great ideas fade in your mind. Frame them onto a poster before noon.", 79),
    ("Courage to Begin", "The secret of getting ahead is simply taking the very first imperfect step.", 79),
    ("Focus on Growth", "Do not compare your chapter one to someone else's chapter twenty. Build your lane.", 79),
    ("Design Your Mindset", "Surround your daily environment with visual reminders of what truly matters to you.", 79),
    ("Action Cures Hesitation", "Ten minutes of focused design work will beat ten hours of overthinking every time.", 79),
    ("Craft with Heart", "When you pour authentic passion into your work, people naturally feel the difference.", 79),
    ("Celebrate Small Wins", "A finished draft today is a milestone worth honoring. Be proud of your momentum.", 79),
    ("Creativity is a Muscle", "The more you experiment and play with layouts, the stronger your instincts become.", 79),
    ("Clarity Follows Action", "You do not need all the answers to start; clarity unfolds as you build.", 79),
    ("Breathe and Refocus", "Step back, simplify the clutter, and let your core message shine with calm confidence.", 79),
    ("Make Today Memorable", "Create something today that your future self will look back on with gratitude.", 79),
    ("Inspire Those Around You", "A single uplifting quote on a beautiful poster can brighten a stranger's whole day.", 79),
    ("Unleash Your Imagination", "Rules are good guides, but creative curiosity is what discovers breakthroughs.", 79),
    ("Own Your Creative Journey", "Trust the process, honor your learning curve, and enjoy every layer you design.", 79),
    ("Patience and Persistence", "Mighty oak trees grow from quiet acorns. Keep tending to your craft daily.", 79),
    ("Be Bold, Be Unapologetic", "Do not water down your style to fit in. Strong designs make memorable impressions.", 79)
]

FEATURE_TIPS = [
    ("1-Tap Background Swap", "Change the entire mood of your poster instantly with hundreds of curated textures.", 76),
    ("Smart Font Pairing", "Discover pre-matched header and body font combinations that guarantee clean legibility.", 76),
    ("Multi-Format Resizing", "Effortlessly resize your flyer between square, portrait story, and wide banner sizes.", 76),
    ("Decorative Badge Stickers", "Add festive ribbons, verified checkmarks, and sales badges with a single tap.", 76),
    ("Precise Snapping Guides", "Center text and align logos effortlessly with intelligent magnet guidelines.", 76),
    ("Custom Color Hex Picker", "Keep your exact brand colors consistent across every single poster you produce.", 76),
    ("Photo Tint & Filters", "Harmonize disparate background photos with matching vintage or warm mood filters.", 76),
    ("Layer Lock & Reorder", "Work on complex multi-element graphics without accidentally shifting background layers.", 76),
    ("Curved Text Magic", "Bend headline text into elegant arches above logos or badges for vintage badge appeal.", 76),
    ("Drop Shadow Controls", "Control blur, angle, and opacity of text shadows for realistic 3D depth.", 76),
    ("Transparent PNG Support", "Import your company logo or cutouts seamlessly with full alpha transparency.", 76),
    ("High-DPI Gallery Export", "Save crisp, print-ready posters without blurriness or compression artifacts.", 76),
    ("Draft Auto-Save", "Never lose a moment of inspiration; pick up right where you left off anytime.", 76),
    ("Aspect Ratio Presets", "Ready-made dimensions for flyers, business cards, story cards, and desktop banners.", 76),
    ("Undo & Redo Freedom", "Experiment fearlessly with quick multi-step undo and redo controls at your fingertips.", 76)
]
