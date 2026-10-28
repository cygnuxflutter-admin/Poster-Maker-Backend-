#!/usr/bin/env python3
"""
Postermaker AI Rich Content Bank Generator (2,000 High-Engagement Drafts)
Generates funny, catchy, emoji-rich, creative, festival, and business notifications.
No repetitive "good morning" or dull prefixes.
"""

import os
import sys
import json
import hashlib
import random
import pymysql

if sys.platform == "win32":
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")

DB_CONFIG = {
    "host": "193.203.184.175",
    "user": "u126066279_poster_db",
    "password": "Oa2C*X1=",
    "database": "u126066279_poster_db",
    "charset": "utf8mb4"
}

# -----------------------------------------------------------------------------------
# CATEGORY DEFINITIONS
# -----------------------------------------------------------------------------------
# 1. WITTY & FUNNY CREATIVE PROMPTS (Slot: 13:00 / 20:00)
# 2. BIRTHDAY & ANNIVERSARY MAGIC (Slot: 09:00 / 17:00)
# 3. FESTIVAL & SEASONAL CELEBRATIONS (Slot: 09:00 / 17:00 / 20:00)
# 4. BUSINESS & PROMOTIONAL FLYERS (Slot: 09:00 / 13:00)
# 5. SOCIAL MEDIA & VIRAL STORIES (Slot: 13:00 / 20:00)
# 6. CREATIVE POSTER DESIGN HACKS (Slot: 13:00 / 17:00)
# 7. PRO POSTERMAKER FEATURE TIPS (Slot: 17:00 / 20:00)
# -----------------------------------------------------------------------------------

def get_rich_drafts():
    drafts = []
    seen = set()

    def add(title, body, cat_name, cat_id, slot, screen="category_detail"):
        key = title.strip().lower() + "|" + body.strip().lower()
        uid = hashlib.sha256(key.encode('utf-8')).hexdigest()
        if uid in seen:
            return False
        seen.add(uid)
        drafts.append({
            "content_uid": uid,
            "title": title.strip(),
            "body": body.strip(),
            "category_id": cat_id,
            "category_name": cat_name,
            "language": "en",
            "target_screen": screen,
            "status": "DRAFT",
            "scheduled_slot": slot
        })
        return True

    # ===============================================================================
    # 1. WITTY, FUNNY & RELATABLE DESIGN HOOKS (Target ~290)
    # ===============================================================================
    funny_templates = [
        ("😂 Why pay ₹5,000 to a designer?", "When you can whip up that same stunning poster in 2 minutes on your phone? Tap to create! 😎", "13:00"),
        ("☕ Chai break + Poster making = Pure bliss", "Sip your hot cutting chai and craft a masterpiece. Fresh trending templates inside! 🎨", "17:00"),
        ("👀 Your competitors are posting better graphics...", "Don't let them steal the spotlight! Grab a killer flyer layout today 🚀", "13:00"),
        ("🛋️ Couch potato to graphic pro in 60s", "No design degree? No problem at all! Pick a layout, edit text, and stun your friends ✨", "20:00"),
        ("🚨 Stop sending boring plain-text invites!", "People actually RSVP when the invitation flyer looks this delicious and exciting 🥳", "17:00"),
        ("🧠 That idea in your head looks legendary", "Don't let it fade away! Bring it to life on canvas before you forget it 💡", "13:00"),
        ("🤫 Secret formula for viral Instagram posts:", "Bold typography + high contrast background. We already baked it into our templates! 🔥", "20:00"),
        ("🍕 Food tastes better when the menu flyer looks fire", "Got a cafe or catering gig? Showcase your dishes with mouth-watering layouts! 😋", "13:00"),
        ("💤 Procrastination level: 100?", "Even your 1-minute quick poster is 100x better than no poster at all! Start now ⚡", "17:00"),
        ("🎯 Stop doom-scrolling, start designing!", "Turn your free 5 minutes into an eye-catching poster that gets tons of likes 📱", "20:00"),
        ("🕶️ Make your friends ask: 'Who designed this?!'", "Drop your selfie into an editorial magazine template and act cool 😎", "20:00"),
        ("😱 Forgot to announce your weekend sale?", "Don't panic! Grab a flash sale banner, type your discount, and post in 30 seconds ⏳", "09:00"),
        ("🍩 Donuts are great, but have you tried crafting a poster?", "Zero calories, 100% creative satisfaction. Jump in and play with fonts today! 🍩", "17:00"),
        ("📈 Your WhatsApp Status is looking lonely...", "Give your contacts something exciting to talk about with a trending aesthetic graphic 💬", "20:00"),
        ("🎪 Life is too short for dull flyers!", "Splash vibrant neon colors and funky geometric stickers on your canvas today 🎨", "13:00"),
        ("🧩 Poster design so easy, even your cat could do it", "Drag, drop, customize, and export in ultra HD. You are a natural creator! 🐾", "17:00"),
        ("💸 Save your budget for what matters", "Get unlimited professional flyers without hiring an expensive agency. Tap to start! 💼", "13:00"),
        ("🎉 You survived another week! Celebrate with art", "Friday feeling calls for a party poster or weekend road trip announcement 🚗", "20:00"),
        ("🔮 The crystal ball says: You need a new story post", "Fresh aesthetic templates just arrived in your favorite categories. Take a peek! ✨", "17:00"),
        ("🚀 Turn your hobby into a real brand today", "Every great business started with a bold logo and a striking first flyer 🌟", "09:00"),
        ("🍕 Pizza is temporary, but a great flyer is forever", "Slice through creative block with our mouthwatering food and cafe templates 🍕", "13:00"),
        ("⚡ Stop scrolling like a zombie!", "Wake up your brain and design something stunning before lunch break ends 🎨", "13:00"),
        ("📱 One good poster can change your whole business", "Get ready for more DMs, calls, and orders with our high-conversion flyer templates 📈", "09:00"),
        ("🕶️ Look at you, turning into a professional designer!", "Who said graphic design was hard? Look at your creations in 'My Designs' 😎", "20:00"),
        ("🚀 Your idea is too good to stay in your head", "Spend 2 minutes putting it on canvas. Download in 4K resolution! ✨", "17:00"),
        ("🔥 Warning: These flyers may attract too many customers!", "Stand out on Instagram, WhatsApp, and printed flyers effortlessly 🛍️", "09:00"),
        ("🎨 Colors talk louder than words", "Master contrasting palettes in our editor. Try dark mode with neon accents today 🌈", "17:00"),
        ("⏰ 60 seconds is all it takes", "Choose a pre-made template, replace the text, export, and boom! You're done ⚡", "20:00")
    ]

    # Combine with diverse angles
    funny_subjects = [
        ("Weekend plans?", "Announce your hangout, gaming night, or cricket match in style! 🏏"),
        ("Got a big announcement?", "Banners that scream 'Notice Me!' are ready in the editor 📢"),
        ("Gym motivation running low?", "Design your own workout goal poster and set it as wallpaper 💪"),
        ("Craving late-night snacks?", "Cook up a creative recipe card or food flyer while you wait 🍔"),
        ("That blank canvas isn't going to fill itself!", "Pick a pre-made design and make it uniquely yours in 1 tap 🖌️"),
        ("Boring greeting cards are banned in 2026!", "Send a customized digital poster card that they will actually frame 💌"),
        ("Need to hype up your webinar?", "Professional keynote flyer templates that make you look like a tech CEO 🎙️"),
        ("Music festival vibes at home?", "Create a vintage psychedelic gig poster with retro typography 🎸"),
        ("Selling your old bike or gadgets?", "A clean flyer sells 5x faster than a blurry camera photo. Try it! 🏍️"),
        ("Pet birthday celebration?", "Yes, your dog deserves a royal birthday party poster. We have puppy crowns! 🐶"),
        ("Boss asked for a quick flyer?", "Blow them away in 5 minutes with our polished corporate flyer layouts 👔"),
        ("Turn your favorite quote into wall art", "Minimalist typography layouts designed for high-res printing 🖼️"),
        ("House party tonight?", "Generate a VIP entry pass flyer and drop it into the group chat 🍻"),
        ("New menu item dropped?", "Highlight your secret sauce with a sizzling culinary flyer 🌶️"),
        ("Lost your creative spark?", "Browse our community trending feed for instant design inspiration ⚡"),
        ("New product dropping soon?", "Build anticipation with a mysterious teaser poster and countdown badge ⏳"),
        ("Hosting an online workshop?", "Professional speaker flyer layouts with dates, topics, and registration links 💻"),
        ("Weekend trekking or camping trip?", "Adventure typography with campfire and pine tree aesthetic backgrounds ⛺"),
        ("Got an adorable pet photo?", "Turn your cat or dog into an illustrated movie poster star in 2 minutes 🎬"),
        ("Need a classy business card?", "Export vertical mini-flyers with your phone, email, and social handles 💳"),
        ("Writing your daily gratitude list?", "Design a peaceful reminder poster for your morning meditation space 🧘‍♂️"),
        ("Garage sale or thrift market?", "Attract neighbors with vintage price tags and bold clearance signs 🛒")
    ]

    funny_variations = [
        ("🔥 Hot Take:", "You don't need Photoshop when Postermaker is in your pocket."),
        ("💡 Pro Tip for Creators:", "The secret to engagement is contrast, punchy fonts, and clear CTA."),
        ("🚀 Quick Challenge:", "Can you build an eye-catching flyer in under 90 seconds? Give it a shot!"),
        ("🌟 Daily Creative Boost:", "Spend 3 minutes making art today. Your future self will thank you."),
        ("⚡ Lightning Fast:", "Zero clutter, instant drag-and-drop. Make your vision come to life now.")
    ]

    cat_name = "Motivational Creative Prompts"
    cat_id = 79
    for base_t, base_b, slot in funny_templates:
        add(base_t, base_b, cat_name, cat_id, slot)
    for subj_t, subj_b in funny_subjects:
        for pfx_t, pfx_b in funny_variations:
            add(f"{pfx_t} {subj_t}", f"{subj_b} {pfx_b}", cat_name, cat_id, "13:00")
            add(f"{subj_t} 🎨", f"{pfx_b} {subj_b}", cat_name, cat_id, "20:00")

    # ===============================================================================
    # 2. BIRTHDAY, ANNIVERSARY & PARTY CELEBRATIONS (Target ~290)
    # ===============================================================================
    cat_name = "Birthday & Anniversary Inspiration"
    cat_id = 47
    bday_seeds = [
        ("🎂 Forgot a Friend's Birthday?", "Don't panic! Create a customized photo poster with funny roasts in 30 seconds 🎁", "09:00"),
        ("🥳 Treat Them Like a VIP Celebrity!", "Golden balloons, confetti frames, and custom wishes for the birthday star 🌟", "09:00"),
        ("💑 Happy Anniversary, Lovebirds! ❤️", "Celebrate your journey with romantic collage templates and sweet captions 💍", "17:00"),
        ("🎈 The Ultimate Birthday Bash Flyer", "DJ music, rooftop vibes, or dinner party? Craft an invite they can't ignore 🎵", "17:00"),
        ("👶 Welcome Little Miracle! Baby Shower Cards", "Adorable pastel templates with cute teddy bears, clouds, and golden stars 🍼", "09:00"),
        ("👑 Sweet 16 & Milestone Birthdays!", "Crown graphics, glitter borders, and bold neon typography for the big milestone 👑", "17:00"),
        ("🎓 Cheers to the Graduate!", "Cap tossing, achievement badges, and bold graduation celebration flyers 🎓", "17:00"),
        ("💌 Modern Save-The-Date Cards", "Minimalist, floral, and royal wedding invitation layouts ready to share 💐", "09:00"),
        ("🎂 Happy Birthday to the Best Mom Ever!", "Warm floral watercolor frames and emotional tribute messages she will cherish 🌸", "09:00"),
        ("🦸‍♂️ Superhero Birthday Flyer for Your Kid!", "Comic book action bubbles, vibrant primary colors, and heroic poster themes 💥", "17:00"),
        ("🥂 25 Years of Togetherness: Silver Jubilee!", "Elegant silver ribbon borders and vintage couple portrait frames 🥂", "17:00"),
        ("🎉 Surprise Party in the Works?", "Keep it top secret with a VIP invitation flyer that gives all the secret details 🤫", "17:00"),
        ("👴 Golden 60th Birthday Celebration", "Royal gold accents, timeless serif typography, and family portrait frames 👑", "09:00"),
        ("🐶 Bark-day Party for Your Furry Best Friend!", "Paws, bones, and adorable dog birthday celebration posters ready to customize 🐾", "17:00"),
        ("🌸 Housewarming & Griha Pravesh Invitation", "Traditional kalash, toran artwork, and auspicious blessings for your new home 🏡", "09:00")
    ]

    bday_modifiers = [
        ("Add their favorite photo", "and customize with sweet nicknames in 1 tap!"),
        ("Swap colors and text", "in seconds to match your exact party theme."),
        ("Download in ultra 4K HD", "and print or share instantly on WhatsApp & Instagram."),
        ("No boring paper cards!", "Digital animated flyers make everyone smile."),
        ("Pick a golden glitter border", "and turn their photo into magazine cover art."),
        ("Surprise them right at midnight", "with a poster that looks like it took hours to make!"),
        ("Choose from 50+ fresh party layouts", "curated by top graphic designers."),
        ("Personalize your warm wishes", "with heart-touching Hindi or English poetry.")
    ]

    for base_t, base_b, slot in bday_seeds:
        add(base_t, base_b, cat_name, cat_id, slot)
        for mod_t, mod_b in bday_modifiers:
            clean_title = base_t.split("!")[0] if "!" in base_t else base_t
            add(f"{clean_title}! 🎂 {mod_t}", f"{base_b} {mod_b}", cat_name, cat_id, slot)
            add(f"🎉 {clean_title} - {mod_t}", f"{mod_b} {base_b}", cat_name, cat_id, slot)

    # ===============================================================================
    # 3. FESTIVAL & SEASONAL CELEBRATIONS (Target ~290)
    # ===============================================================================
    cat_name = "Festival & Seasonal Poster Ideas"
    cat_id = 55
    festivals = [
        ("Diwali 🪔", "Happy Diwali! Spread golden light, diyas, fireworks, and prosperity wishes ✨", "Glow with golden oil lamps, rangoli patterns, and auspicious blessings."),
        ("Holi 🎨", "Bura Na Mano, Holi Hai! Vibrant color splash flyers and celebration parties 🌈", "Add organic gulal textures, water gun stickers, and party details."),
        ("Navratri 💃", "Chalo Garba Ramva! Dandiya night passes, traditional beats, and ethnic vibes 🥁", "Vibrant chaniya choli silhouettes, dandiya sticks, and rhythmic designs."),
        ("Eid Mubarak 🌙", "Eid-ul-Fitr Mubarak! Crescent moon, royal emerald, and peace greetings 🕌", "Warm blessings, exquisite Islamic geometric patterns, and golden crescents."),
        ("Christmas 🎄", "Merry Christmas! Festive pine wreaths, Santa vibes, and joyful party flyers 🎅", "Snowflakes, jingle bells, candy canes, and cozy winter layouts."),
        ("New Year 2027 🎆", "Happy New Year! Golden countdown, champagne sparkle, and fresh goals 🍾", "Toast to new beginnings with sparkling glitter and bold typography."),
        ("Makar Sankranti / Uttarayan 🪁", "Kai Po Che! Kites soaring high, sweet tilgul, and sunny sky status posters 🪁", "Colorful patangs, firki graphics, and cheerful sun celebration art."),
        ("Independence Day 🇮🇳", "Proud to be Indian! Saluting the tricolor with patriotic fervor 🇮🇳", "Saffron, white, and green gradients with Ashoka Chakra and national pride."),
        ("Republic Day 🇮🇳", "Happy Republic Day! Celebrating India's constitution, courage, and unity 🇮🇳", "Bold patriotic typography, freedom fighter tributes, and tricolor banners."),
        ("Ganesh Chaturthi 🙏", "Ganpati Bappa Morya! Auspicious arrival invitations and modak feasts 🌸", "Divine Ganesha artwork, traditional dhol tasha vibes, and puja invites."),
        ("Raksha Bandhan 🧵", "Celebrate the unbreakable bond of brother and sister with custom cards 🎁", "Sacred rakhi threads, emotional captions, and sweet childhood photo frames."),
        ("Janmashtami 🦚", "Happy Janmashtami! Peacock feathers, flute melodies, and Krishna blessings 🦚", "Matki phod competition flyers and serene Bal Gopal portrait cards."),
        ("Dussehra 🏹", "Vijayadashami! Victory of good over evil, golden shami leaves, and joy 🏹", "Bow and arrow motifs, Ravana dahan flyers, and festive family blessings."),
        ("Maha Shivratri 🔱", "Har Har Mahadev! Divine blessings of Lord Shiva with serene trident art 🕉️", "Trishul, damru, and sacred Om graphics with peaceful meditative backdrops."),
        ("Onam 🌾", "Happy Onam! Royal pookkalam flower carpets and grand sadhya feast invites 🌼", "Boat race flyers, Kasavu saree elegance, and traditional Kerala greetings."),
        ("Women's Day 🌸", "Happy Women's Day! Honoring strength, grace, and limitless achievements 👑", "Empowering quotes, floral frames, and celebration cards."),
        ("Mother's Day 💐", "Happy Mother's Day! To the queen of our hearts and master of our home 💖", "Emotional portraits, delicate pastel bouquets, and sweet notes."),
        ("Father's Day 👔", "Happy Father's Day! Cheers to my superhero, guide, and greatest friend 🏆", "Gentleman aesthetic, vintage retro badges, and heartfelt tributes."),
        ("Valentine's Day ❤️", "Be My Valentine! Romantic love letters, red roses, and cute couple frames 🌹", "Candlelight aesthetic, glowing heart borders, and romantic captions."),
        ("Teacher's Day 📚", "Happy Teacher's Day! Thank you for shaping minds and igniting dreams 🎓", "Chalkboard designs, apple icons, and inspiring gratitude messages."),
        ("Yoga Day 🧘‍♀️", "International Yoga Day! Balance your body, calm your mind, elevate life 🕉️", "Sunrise silhouettes, lotus motifs, and serene wellness flyers.")
    ]

    fest_variations = [
        ("Design your festival wish in 1 min", "Add your family photo and name for that personal touch."),
        ("Perfect for WhatsApp Status & Story", "Bright, high-contrast colors that everyone will love."),
        ("Send warm wishes to clients & customers", "Brand it with your company logo and phone number!"),
        ("Community celebration or apartment party?", "Create a professional notice board flyer today."),
        ("Don't forward someone else's generic wish", "Make an original, gorgeous greeting card with your own touch!"),
        ("Trending festive layouts are now live", "Tap to explore authentic cultural art and calligraphy fonts.")
    ]

    for fest_name, fest_headline, fest_desc in festivals:
        add(f"✨ {fest_headline}", f"{fest_desc} Customize in Postermaker now! 🪔", cat_name, cat_id, "09:00")
        for v_title, v_desc in fest_variations:
            add(f"{fest_name}: {v_title}", f"{fest_desc} {v_desc}", cat_name, cat_id, "17:00")
            add(f"🪔 {fest_name} Celebration Poster", f"{v_desc} {fest_desc}", cat_name, cat_id, "20:00")

    # ===============================================================================
    # 4. BUSINESS & PROMOTIONAL FLYERS (Target ~290)
    # ===============================================================================
    cat_name = "Business & Promotional Poster Ideas"
    cat_id = 11
    biz_seeds = [
        ("🔥 MEGA FLASH SALE: Up to 50% Off!", "Turn foot traffic into loyal customers! Bold discount badges and urgent red banners 🛍️", "09:00"),
        ("📢 Grand Opening Announcement!", "Welcome your neighborhood with high-impact store launch flyers and ribbon graphics 🏪", "09:00"),
        ("🍔 Sizzling Food Special of the Day!", "Make mouths water with gourmet burger, pizza, and beverage promo cards 🍕", "13:00"),
        ("💆‍♀️ Weekend Spa & Salon Pamper Package", "Luxury beauty discounts, clean minimalist pastel aesthetics, and booking CTAs 💅", "09:00"),
        ("🏡 Prime Real Estate Listing Alert!", "Showcase luxury properties, floor plans, and open-house dates with elegant frames 🔑", "09:00"),
        ("👗 New Fashion Arrivals Are In Stock!", "High-fashion lookbook flyers, seasonal catalogs, and boutique display posters 👠", "13:00"),
        ("🚗 Quick Oil Change & Auto Service Deal", "Trustworthy blue and yellow mechanic flyers that bring car owners to your garage 🚙", "09:00"),
        ("🏋️ New Year, New You: Gym Membership Deal", "High-energy workout flyers, personal training specials, and fitness motivation 💪", "09:00"),
        ("🎓 Admissions Open for 2026-2027!", "School, coaching, and academy banners with modern badge highlights and course lists 📚", "09:00"),
        ("💻 Freelance Service & Agency Showcase", "Highlight web design, marketing, and SEO offerings with clean corporate grids 📊", "13:00"),
        ("🍕 Buy 1 Get 1 Free This Weekend!", "Irresistible dining offers that pack your restaurant tables on Friday night 🍻", "13:00"),
        ("🦷 Free Dental Checkup Camp!", "Clean medical and healthcare awareness flyers for clinics and doctor visits 🩺", "09:00"),
        ("🚚 Express Home Delivery Now Available!", "Let your local customers know they can order straight to their door in 30 mins 📦", "13:00"),
        ("🏷️ Clearance Sale: Everything Must Go!", "Create urgency with countdown stickers, yellow caution stripes, and bold text ⏳", "09:00"),
        ("✈️ Holiday Tour Packages at Dream Prices!", "Wanderlust travel flyers with tropical beach backdrops and tour itineraries 🏖️", "13:00"),
        ("🍰 Freshly Baked Cakes & Pastries!", "Delicious dessert showcase flyers for bakeries, anniversaries, and custom orders 🎂", "13:00"),
        ("📸 Professional Photoshoot Bookings Open!", "Capture timeless portraits, wedding candid moments, and studio packages 📷", "17:00"),
        ("📱 Mobile & Gadgets Exchange Festival!", "Top trade-in values, smartphone upgrades, and accessories discount flyers 📱", "09:00"),
        ("🐾 Pet Care & Grooming Spa Day!", "Give your pup a royal bath and haircut. Adorable paw-print flyer templates 🐕", "13:00"),
        ("☕ Coffee House Acoustic Live Night!", "Intimate music sessions, cozy latte vibes, and neighborhood gathering flyers 🎸", "20:00"),
        ("🧼 Eco Car Wash & Detailing Special", "High-gloss ceramic coatings, interior deep clean, and quick shine deals 🚘", "09:00")
    ]

    biz_enhancers = [
        ("Add your shop logo & contact number", "in 1 tap to turn viewers into immediate callers!"),
        ("Use bold contrast for mobile screens", "so your discounts catch eyes while people scroll."),
        ("Place a clear 'Call Now' or 'Visit Store' badge", "and watch your footfall multiply today."),
        ("Ready in square or landscape sizes", "ready for Instagram feed, Facebook ads, or flyer print."),
        ("No graphic agency costs required", "Design your own retail campaigns whenever stock arrives."),
        ("Professional typography presets built-in", "give your local business an international corporate polish!"),
        ("Pick high-converting color palettes", "like red-yellow for food or blue-white for medical services."),
        ("Export in 300 DPI high resolution", "and print paper leaflets directly at your local print shop.")
    ]

    for b_title, b_body, slot in biz_seeds:
        add(b_title, b_body, cat_name, cat_id, slot)
        for e_t, e_b in biz_enhancers:
            clean_b_title = b_title.split("!")[0] if "!" in b_title else b_title
            add(f"{clean_b_title}! 💼 {e_t}", f"{b_body} {e_b}", cat_name, cat_id, slot)
            add(f"📢 {clean_b_title} - {e_t}", f"{e_b} {b_body}", cat_name, cat_id, slot)

    # ===============================================================================
    # 5. SOCIAL MEDIA & VIRAL STORIES (Target ~285)
    # ===============================================================================
    cat_name = "Social Media Design Inspiration"
    cat_id = 34
    social_seeds = [
        ("📱 Stop the Scroll on Instagram Stories!", "Vertical 9:16 templates designed to grab thumbs instantly and boost your story views ⚡", "20:00"),
        ("💬 WhatsApp Status Posters That Make People Reply", "Thoughtful aesthetic layouts, morning thoughts, and clean typography badges 💬", "13:00"),
        ("🔴 YouTube Thumbnails That Demand a Click!", "High-contrast neon borders, expressive badge overlays, and punchy titles that win clicks 🎬", "20:00"),
        ("✨ Minimalist Aesthetic Quotes for Your Grid", "Clean serif typography, earthy neutral tones, and soothing negative space 🌿", "13:00"),
        ("🎙️ Promote Your Podcast or Latest Blog Post", "Soundwave graphics, quote highlights, and clean swipe-up design elements 🎧", "20:00"),
        ("🔥 Trending Meme Templates for Your Brand", "Connect with your audience through humor, relatable comic bubbles, and witty banter 🤪", "20:00"),
        ("🏆 Showcase Client Reviews & Testimonials!", "Star rating stickers, quotation marks, and verified badge layouts for trust 🌟", "13:00"),
        ("📊 Before & After Transformation Posters", "Split-screen comparisons for fitness, makeup, home renovation, or design work 🔄", "13:00"),
        ("❓ Ask Me Anything / Poll Sticker Backgrounds", "Clean interactive frames that leave perfect breathing room for IG questions 🗳️", "20:00"),
        ("💡 3 Actionable Tips of the Day", "Carousel cover flyers and numbered infographic cards that get tons of saves 📌", "13:00"),
        ("🎵 What's on Your Playlist? Share in Style", "Vinyl record artwork, cassette tape retro frames, and album cover layouts 📻", "20:00"),
        ("📍 Local Travel Vlog & Road Trip Highlights", "Polaroid film strips, compass stamps, and mountain silhouette status cards 🏕️", "20:00"),
        ("☕ Monday Morning Coffee Vibes", "Warm latte art photos, rustic wood textures, and energizing Monday motivation ☕", "09:00"),
        ("🎉 Thank You for 10K Followers!", "Celebrate milestones with confetti cannons, metallic balloons, and sincere thanks 🥳", "20:00"),
        ("🗓️ Mark Your Calendar: Event Countdown", "Big bold numbers counting down the days to your product launch or live stream ⏳", "13:00")
    ]

    social_tips = [
        ("Pick bold, readable fonts", "so mobile users can digest your headline in under 1 second."),
        ("Pair with trending background gradients", "for an instant modern influencer aesthetic."),
        ("Add sticker overlays and arrows", "to guide the viewer's eyes directly to your call to action."),
        ("One-click re-dimensioning", "swaps your design between square feed and vertical reel cover."),
        ("Save as high quality PNG", "to avoid blurry compression when uploading to social platforms."),
        ("Keep your text centered", "to prevent social UI icons from covering your important words."),
        ("Aesthetic pastel palettes", "get 40% more saves and shares on creative lifestyle boards."),
        ("Dark mode contrast highlights", "make neon text pop vibrantly on AMOLED screens.")
    ]

    for s_title, s_body, slot in social_seeds:
        add(s_title, s_body, cat_name, cat_id, slot)
        for st_t, st_b in social_tips:
            clean_s_title = s_title.split("!")[0] if "!" in s_title else s_title
            add(f"{clean_s_title}! 📱 {st_t}", f"{s_body} {st_b}", cat_name, cat_id, slot)
            add(f"✨ {clean_s_title} - {st_t}", f"{st_b} {s_body}", cat_name, cat_id, slot)

    # ===============================================================================
    # 6. CREATIVE POSTER DESIGN HACKS & ART INSPIRATION (Target ~285)
    # ===============================================================================
    cat_name = "Creative Poster Ideas"
    cat_id = 76
    creative_seeds = [
        ("🎨 Dark Mode + Neon Glow = Instant Magic", "Give your flyer an electric look with cyberpunk neon accents on rich obsidian backdrops ⚡", "13:00"),
        ("📐 The 60-30-10 Color Rule for Posters", "60% dominant base tone, 30% secondary structure, 10% punchy accent. Flawless harmony! 🌈", "13:00"),
        ("🔤 Don't Mix More Than 2 Font Families!", "Pair a bold headline sans-serif with an elegant readable body font for clean hierarchy ✍️", "17:00"),
        ("🪄 The Superpower of Negative Space", "Give your headline room to breathe. Clutter-free posters always look 10x more expensive 🕊️", "13:00"),
        ("🌅 Gradient Magic That Breathes Life Into Art", "Sunset blends, ocean fades, and duotone backdrops create instant visual depth 🌇", "17:00"),
        ("🖼️ Drop Shadows: Subtle is Always Superior", "Soft blurred shadows give your stickers, cards, and text realistic 3D depth 🌟", "17:00"),
        ("⚡ High-Contrast Typography That Demands Attention", "Put bright lemon yellow on deep midnight navy. It's scientifically impossible to ignore! 🐝", "13:00"),
        ("🎭 Retro 90s Grain & Vintage Film Vibes", "Add subtle noise and warm analog light leaks for that timeless nostalgic aesthetic 📼", "20:00"),
        ("🌿 Botanical & Floral Watercolor Accents", "Delicate eucalyptus leaves and gold foil veins bring gentle organic elegance 🍃", "13:00"),
        ("🧱 Grid Layouts That Never Fail", "Align your elements along clean thirds to create intuitive, satisfying visual balance 📐", "17:00"),
        ("🔥 Glassmorphism: The Modern Frosted Look", "Translucent cards with soft blur make your layered elements look sleek and futuristic 💎", "20:00"),
        ("🎯 The Visual F-Pattern in Poster Design", "People read in an F-shape: Headline top left, key bullet center, CTA bottom right 👁️", "13:00"),
        ("🌈 Monochromatic Palettes for Sleek Branding", "Use 4 shades of the exact same color family for an effortless, ultra-chic look 🎨", "17:00"),
        ("💥 Diagonal Lines Inject Instant Kinetic Energy", "Tilt your background banner 15 degrees to turn a static flyer into a dynamic poster 🏃‍♂️", "13:00"),
        ("👑 Golden Ratio Typography Scales", "Scale headline at 36pt, subhead at 24pt, body at 14pt. Natural mathematical elegance! 📏", "17:00")
    ]

    creative_hacks = [
        ("Try this in the editor today", "Pick any blank canvas and experiment with this styling trick."),
        ("See the difference immediately", "Swap out your standard layout and watch the design elevate."),
        ("Perfect for portfolio & client work", "Impress everyone with sophisticated graphic design principles."),
        ("Ready-made presets available inside", "Our design library already includes these professional ratios."),
        ("Takes less than 60 seconds", "Tweak opacity and shadows using our intuitive bottom slider tools."),
        ("Bookmark this quick tip", "and apply it to your next poster project for maximum visual punch!"),
        ("Works on both photos and text", "Blend seamlessly with your background imagery in 1 tap."),
        ("Design like an experienced agency", "without ever opening complicated desktop software.")
    ]

    for c_title, c_body, slot in creative_seeds:
        add(c_title, c_body, cat_name, cat_id, slot)
        for h_t, h_b in creative_hacks:
            clean_c_title = c_title.split("!")[0] if "!" in c_title else c_title
            add(f"{clean_c_title}! 🎨 {h_t}", f"{c_body} {h_b}", cat_name, cat_id, slot)
            add(f"💡 {clean_c_title} - {h_t}", f"{h_b} {c_body}", cat_name, cat_id, slot)

    # ===============================================================================
    # 7. PRO POSTERMAKER FEATURE TIPS & APP SUPERPOWERS (Target ~280)
    # ===============================================================================
    cat_name = "Poster-Making Features & Tips"
    cat_id = 76
    feature_seeds = [
        ("✂️ 1-Tap Background Remover is Like Magic!", "Cut out portraits, cars, jewelry, and products cleanly with zero tedious lassoing 🪄", "17:00"),
        ("🏷️ Thousands of Free Badges & Stickers", "Discount tags, gold ribbons, sparkle bursts, and emoji packs waiting inside 🎁", "17:00"),
        ("📏 Auto-Resize for Every Social Network", "Convert 1 design into Instagram Post, Story, Facebook Banner, and Flyer instantly 📐", "13:00"),
        ("🎨 Save Your Custom Brand Palette & Logo", "Stamp your watermark and lock your brand hex codes for 1-tap consistency on every flyer 🏆", "09:00"),
        ("🖨️ Ultra-High 4K Crystal Clear Export", "Never settle for blurry pixels. Export crisp vector-quality graphics ready for print 🖨️", "17:00"),
        ("🧲 Precision Snapping & Smart Guides", "Align your text boxes and logos dead-center automatically with magnetic snap lines 🧲", "17:00"),
        ("🔒 Lock Layers to Design Without Headache", "Freeze your background in place so you can reposition text without accidental slips 🔒", "20:00"),
        ("🌈 Color Eyedropper Tool for Exact Matches", "Sample any exact color directly from your product photo to match your text perfectly 🧪", "13:00"),
        ("🔠 Curved Text & Circular Badge Typography", "Bend your titles around seals, logos, and medals with our smooth curvature slider ⭕", "17:00"),
        ("🖼️ Photo Masking Inside Custom Shapes", "Fit photos into stars, hearts, coffee cups, or letters with 1 quick tap 🌟", "20:00"),
        ("↩️ Unlimited Undo & Redo History", "Made an accidental mistake? Don't stress! Tap Undo to rewind your edits instantly ⏪", "17:00"),
        ("🎚️ Opacity & Blend Mode Experiments", "Multiply, Screen, and Overlay modes let your textures integrate like real printed paper 📜", "20:00"),
        ("📁 Auto-Save to 'My Creations' Offline", "Your work in progress is always saved safely. Resume your edits anytime, anywhere 💾", "17:00"),
        ("🔤 500+ Indian & International Fonts", "From bold Hindi calligraphy to elegant European serif types, all unlocked for free ✍️", "13:00"),
        ("📤 Direct Share to WhatsApp & Instagram", "Export and send straight to your customers or story with one seamless tap 🚀", "20:00")
    ]

    feature_prompts = [
        ("Unlock your creative speed", "Explore this tool inside the editor toolbar right now!"),
        ("Saves you hours of manual editing", "Let Postermaker's smart engine do the heavy lifting."),
        ("Did you know this feature exists?", "Open the editor to discover all hidden graphic shortcuts."),
        ("Master this in 30 seconds", "and your posters will look indistinguishable from agency work!"),
        ("Try it on your next flyer", "Combine with our pre-built sticker library for extra pop."),
        ("Completely free to use", "No subscriptions, no hidden watermarks. Pure creative freedom!"),
        ("Recommended by top digital creators", "The fastest way to produce consistent brand graphics."),
        ("Tap below to test it out", "Your next viral graphic is just a few taps away!")
    ]

    for f_title, f_body, slot in feature_seeds:
        add(f_title, f_body, cat_name, cat_id, slot)
        for fp_t, fp_b in feature_prompts:
            clean_f_title = f_title.split("!")[0] if "!" in f_title else f_title
            add(f"{clean_f_title}! 🛠️ {fp_t}", f"{f_body} {fp_b}", cat_name, cat_id, slot)
            add(f"🚀 {clean_f_title} - {fp_t}", f"{fp_b} {f_body}", cat_name, cat_id, slot)

    # ===============================================================================
    # 8. DAILY WITTY CREATIVE QUOTES & SPARK (To round out 2,000+ perfectly)
    # ===============================================================================
    cat_name = "Motivational Creative Prompts"
    cat_id = 79
    quote_seeds = [
        ("🌟 Creativity is intelligence having fun!", "Quote of the day by Albert Einstein. Turn your playful ideas into canvas art ✨", "09:00"),
        ("🚀 Don't wait for inspiration, create it!", "Great ideas appear while your hands are working. Open the editor and see 🎨", "13:00"),
        ("💡 Good design is obvious, great design is transparent", "Focus on clarity and let your message do the talking today 🕊️", "17:00"),
        ("⚡ Action cures creative block", "Staring at a blank wall? Pick a trending template and let momentum take over 🏃‍♂️", "20:00"),
        ("🎯 Design is thinking made visual", "Give your thoughts a colorful, memorable home on your feed today 📱", "09:00"),
        ("🔥 Simplicity is the ultimate sophistication", "Less is always more. Clean typography, confident colors, and breathing room 🌿", "13:00"),
        ("🎨 You are only 1 poster away from your next big client", "Showcase your portfolio, discounts, and services with pride 💼", "17:00"),
        ("☕ Coffee in one hand, confidence in the other", "Power through your afternoon tasks with an uplifting quote poster ☕", "13:00"),
        ("🏆 Every expert was once an amateur", "Don't be afraid to experiment with new colors, angles, and sticker layouts 🌟", "20:00"),
        ("✨ Make something today that makes you smile", "Art is your personal therapy. Craft a heartfelt wallpaper or quote banner 💖", "20:00")
    ]
    quote_variations = [
        ("Design your own quote wallpaper", "and keep your spirits high all week long."),
        ("Customize the font & color in 30 seconds", "and share to your WhatsApp status!"),
        ("Pick a serene nature backdrop", "and let the words inspire your friends today."),
        ("Add your name as the author watermark", "and claim your creative inspiration proudly."),
        ("One tap to export in crystal 4K", "Ready for your phone lockscreen or desktop.")
    ]
    for q_t, q_b, slot in quote_seeds:
        add(q_t, q_b, cat_name, cat_id, slot)
        for qv_t, qv_b in quote_variations:
            clean_q = q_t.split("!")[0] if "!" in q_t else q_t
            add(f"✨ {clean_q} - {qv_t}", f"{q_b} {qv_b}", cat_name, cat_id, slot)
            add(f"💡 {clean_q}! 🌟 {qv_t}", f"{qv_b} {q_b}", cat_name, cat_id, slot)

    return drafts

def populate_database(drafts, target=2000):
    # Slice to exact target
    selected = drafts[:target]
    print(f"[*] Generated {len(drafts)} distinct drafts. Selecting exactly {len(selected)}...")

    conn = pymysql.connect(**DB_CONFIG)
    with conn.cursor() as cur:
        # Check current sent/processing records to keep them safe
        cur.execute("SELECT id, content_uid FROM push_notification_bank WHERE status IN ('SENT', 'PROCESSING')")
        protected = cur.fetchall()
        print(f"[*] Preserving {len(protected)} SENT/PROCESSING records.")

        # Wipe out old DRAFT items
        cur.execute("DELETE FROM push_notification_bank WHERE status = 'DRAFT'")
        print(f"[*] Cleaned old DRAFT records from push_notification_bank.")

        # Insert fresh rich drafts
        insert_sql = """
            INSERT INTO push_notification_bank 
            (content_uid, title, body, category_id, category_name, language, target_screen, status, scheduled_slot)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE title=VALUES(title), body=VALUES(body), category_name=VALUES(category_name)
        """

        batch = []
        inserted = 0
        for d in selected:
            batch.append((
                d["content_uid"],
                d["title"],
                d["body"],
                d["category_id"],
                d["category_name"],
                d["language"],
                d["target_screen"],
                d["status"],
                d["scheduled_slot"]
            ))
            if len(batch) >= 100:
                cur.executemany(insert_sql, batch)
                conn.commit()
                inserted += len(batch)
                batch = []

        if batch:
            cur.executemany(insert_sql, batch)
            conn.commit()
            inserted += len(batch)

        print(f"[SUCCESS] Inserted {inserted} rich, emoji-filled, witty drafts into database!")

        cur.execute("SELECT status, COUNT(*) as cnt FROM push_notification_bank GROUP BY status")
        print("[*] Final Bank Status:", cur.fetchall())

    conn.close()

if __name__ == "__main__":
    all_drafts = get_rich_drafts()
    print(f"[*] Total unique drafts generated: {len(all_drafts)}")
    populate_database(all_drafts, target=2000)
