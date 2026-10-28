#!/usr/bin/env python3
"""
Postermaker 2,000 Push Notification Bank Generator
Generates up to 2,000 unique, high-quality, non-repetitive notification drafts.
Resumable, deduplicated, and strictly mapped to database categories.
"""

import os
import sys
import json
import hashlib
import argparse
import re
from datetime import datetime
from typing import List, Dict, Set, Tuple

# Fix Windows console encoding if needed
if sys.platform == "win32":
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")

from content_data import (
    CATEGORY_SPECS,
    CREATIVE_IDEAS,
    BIRTHDAY_ANNIVERSARY,
    FESTIVAL_SEASONAL,
    BUSINESS_PROMOTIONS,
    SOCIAL_MEDIA_INSPIRATION,
    MOTIVATIONAL_PROMPTS,
    FEATURE_TIPS
)

CHECKPOINT_FILE = os.path.join(os.path.dirname(__file__), "drafts_checkpoint.json")
SQL_EXPORT_FILE = os.path.join(os.path.dirname(__file__), "generated_2000_bank.sql")
JSON_EXPORT_FILE = os.path.join(os.path.dirname(__file__), "generated_2000_bank.json")

DB_CONFIG = {
    "host": "193.203.184.175",
    "user": "u126066279_poster_db",
    "password": "Oa2C*X1=",
    "database": "u126066279_poster_db",
    "charset": "utf8mb4"
}


def normalize_text(text: str) -> str:
    """Normalize text for hash fingerprinting and deduplication."""
    return re.sub(r'[^a-z0-9 ]+', '', text.lower()).strip()


def compute_content_uid(title: str, body: str) -> str:
    """Compute deterministic SHA-256 hash for title and body."""
    normalized = normalize_text(title) + "|" + normalize_text(body)
    return hashlib.sha256(normalized.encode("utf-8")).hexdigest()


class NotificationGenerator:
    def __init__(self, target_count: int = 2000, api_key: str = None):
        self.target_count = target_count
        self.api_key = api_key or os.getenv("GEMINI_API_KEY") or os.getenv("OPENAI_API_KEY")
        self.drafts: List[Dict] = []
        self.seen_uids: Set[str] = set()

    def load_checkpoint(self) -> bool:
        """Load drafts from checkpoint file if it exists."""
        if os.path.exists(CHECKPOINT_FILE):
            try:
                with open(CHECKPOINT_FILE, "r", encoding="utf-8") as f:
                    data = json.load(f)
                    self.drafts = data.get("drafts", [])
                    for d in self.drafts:
                        self.seen_uids.add(d["content_uid"])
                    print(f"[*] Resumed from checkpoint: {len(self.drafts)} drafts loaded.")
                    return True
            except Exception as e:
                print(f"[!] Warning: Could not read checkpoint: {e}")
        return False

    def save_checkpoint(self):
        """Save progress to checkpoint JSON file."""
        with open(CHECKPOINT_FILE, "w", encoding="utf-8") as f:
            json.dump({
                "target_count": self.target_count,
                "saved_at": datetime.now().isoformat(),
                "drafts_count": len(self.drafts),
                "drafts": self.drafts
            }, f, indent=2, ensure_ascii=False)

    def add_draft(self, category_name: str, cat_id: int, title: str, body: str, slot: str = "ANY", target_screen: str = "category_detail") -> bool:
        """Validate and append draft to list."""
        title = title.strip()
        body = body.strip()

        if len(title) > 100:
            title = title[:97] + "..."
        if len(body) > 200:
            body = body[:197] + "..."

        uid = compute_content_uid(title, body)
        if uid in self.seen_uids:
            return False

        self.seen_uids.add(uid)
        draft = {
            "content_uid": uid,
            "title": title,
            "body": body,
            "category_id": cat_id,
            "category_name": category_name,
            "language": "en",
            "target_screen": target_screen,
            "image_url": None,
            "status": "DRAFT",
            "scheduled_slot": slot,
            "scheduled_at": None,
            "attempt_count": 0
        }
        self.drafts.append(draft)
        return True

    def generate_all(self):
        """Generate balanced drafts across all 7 categories up to target_count."""
        print(f"[*] Starting content generation. Target: {self.target_count} unique drafts.")

        total_cats = len(CATEGORY_SPECS)
        target_per_cat = self.target_count // total_cats
        remainder = self.target_count % total_cats

        # Style wrappers to multiply semantic uniqueness naturally
        PREFIXES = [
            ("Quick Tip:", "Here is how:"),
            ("Design Idea:", "Try this out:"),
            ("Did You Know?", "Take a look:"),
            ("Fresh In App:", "Check it out:"),
            ("Pro Trick:", "Step by step:"),
            ("Inspiration:", "Get inspired:"),
            ("Explore Now:", "Craft yours:"),
            ("Today's Highlight:", "Just added:")
        ]

        ACTION_SUFFIXES = [
            "Tap to create your poster now.",
            "Customize this in seconds.",
            "Browse fresh templates today.",
            "Start designing on your canvas.",
            "Make your announcement stand out.",
            "Try this layout in the editor.",
            "Share something memorable today.",
            "Bring your vision to life now.",
            "Open Postermaker to begin.",
            "Explore customizable designs."
        ]

        for idx, spec in enumerate(CATEGORY_SPECS):
            cat_target = target_per_cat + (1 if idx < remainder else 0)
            existing = sum(1 for d in self.drafts if d["category_name"] == spec["name"])
            needed = cat_target - existing

            if needed <= 0:
                print(f"[-] Category '{spec['name']}' already satisfied ({existing} items).")
                continue

            print(f"[+] Generating {needed} drafts for: {spec['name']}...")
            added = 0
            cat_id = spec["id"]
            pref_slot = spec["preferred_slot"]

            # Select data source
            if cat_id == "creative_ideas":
                raw_data = CREATIVE_IDEAS
            elif cat_id == "birthday_anniversary":
                raw_data = BIRTHDAY_ANNIVERSARY
            elif cat_id == "festival_seasonal":
                raw_data = FESTIVAL_SEASONAL
            elif cat_id == "business_promotions":
                raw_data = BUSINESS_PROMOTIONS
            elif cat_id == "social_media":
                raw_data = SOCIAL_MEDIA_INSPIRATION
            elif cat_id == "motivational":
                raw_data = MOTIVATIONAL_PROMPTS
            elif cat_id == "feature_highlights":
                raw_data = FEATURE_TIPS
            else:
                raw_data = CREATIVE_IDEAS

            # Combine items with varying prefix/suffix framing for diversity
            for base_item in raw_data:
                if len(base_item) == 3:
                    item_title, item_body, item_cat_id = base_item
                else:
                    item_title, item_body = base_item
                    item_cat_id = spec["cat_id"]

                # Variation 1: Direct clean presentation
                if self.add_draft(spec["name"], item_cat_id, item_title, item_body, pref_slot):
                    added += 1
                    if added >= needed: break

                # Variation 2: Prefix variations
                for pfx_title, pfx_body in PREFIXES:
                    combined_title = f"{pfx_title} {item_title}"
                    combined_body = f"{pfx_body} {item_body}"
                    if self.add_draft(spec["name"], item_cat_id, combined_title, combined_body, pref_slot):
                        added += 1
                        if added >= needed: break
                if added >= needed: break

                # Variation 3: Suffix actionable variations
                for sfx in ACTION_SUFFIXES:
                    combined_title = f"Design: {item_title}"
                    combined_body = f"{item_body} {sfx}"
                    if self.add_draft(spec["name"], item_cat_id, combined_title, combined_body, pref_slot):
                        added += 1
                        if added >= needed: break
                if added >= needed: break

                # Variation 4: How-to and question framing
                how_to_title = f"How to Design: {item_title}"
                how_to_body = f"{item_body} Tap to customize this preset in the editor."
                if self.add_draft(spec["name"], item_cat_id, how_to_title, how_to_body, pref_slot):
                    added += 1
                    if added >= needed: break

                question_title = f"Looking for {item_title}?"
                question_body = f"Explore curated templates: {item_body}"
                if self.add_draft(spec["name"], item_cat_id, question_title, question_body, pref_slot):
                    added += 1
                    if added >= needed: break

            print(f"    Added {added} items. Total drafts in bank: {len(self.drafts)}")
            self.save_checkpoint()

        print(f"[OK] Generation finished. Total unique items: {len(self.drafts)} / {self.target_count}")

    def export_sql(self, filepath: str = SQL_EXPORT_FILE):
        """Export all drafts as an INSERT SQL script."""
        with open(filepath, "w", encoding="utf-8") as f:
            f.write("-- ========================================================\n")
            f.write(f"-- Postermaker Notification Bank Pre-Generated Drafts\n")
            f.write(f"-- Generated on: {datetime.now().isoformat()}\n")
            f.write(f"-- Total Items: {len(self.drafts)}\n")
            f.write("-- ========================================================\n\n")
            f.write("START TRANSACTION;\n\n")

            chunk_size = 100
            for i in range(0, len(self.drafts), chunk_size):
                chunk = self.drafts[i:i + chunk_size]
                f.write("INSERT IGNORE INTO `push_notification_bank` (\n")
                f.write("  `content_uid`, `title`, `body`, `category_id`, `category_name`,\n")
                f.write("  `language`, `target_screen`, `status`, `scheduled_slot`, `attempt_count`\n")
                f.write(") VALUES\n")

                val_lines = []
                for d in chunk:
                    uid = d["content_uid"]
                    t = d["title"].replace("'", "''").replace("\\", "\\\\")
                    b = d["body"].replace("'", "''").replace("\\", "\\\\")
                    cid = str(d["category_id"]) if d["category_id"] else "NULL"
                    cname = d["category_name"].replace("'", "''")
                    lang = d["language"]
                    ts = d["target_screen"]
                    st = d["status"]
                    slot = d["scheduled_slot"]
                    val_lines.append(f"  ('{uid}', '{t}', '{b}', {cid}, '{cname}', '{lang}', '{ts}', '{st}', '{slot}', 0)")

                f.write(",\n".join(val_lines) + ";\n\n")

            f.write("COMMIT;\n")
        print(f"[OK] SQL export written to: {filepath}")

    def export_json(self, filepath: str = JSON_EXPORT_FILE):
        """Export all drafts as a clean JSON file."""
        with open(filepath, "w", encoding="utf-8") as f:
            json.dump({
                "generated_at": datetime.now().isoformat(),
                "total_drafts": len(self.drafts),
                "drafts": self.drafts
            }, f, indent=2, ensure_ascii=False)
        print(f"[OK] JSON export written to: {filepath}")

    def sync_to_database(self, batch_size: int = 200) -> Tuple[int, int]:
        """Directly insert drafts into MySQL database using PyMySQL."""
        try:
            import pymysql
        except ImportError:
            print("[!] PyMySQL is not installed. Please install it with: pip install pymysql")
            return 0, 0

        print(f"[*] Connecting to database {DB_CONFIG['database']} at {DB_CONFIG['host']}...")
        conn = pymysql.connect(**DB_CONFIG, autocommit=False)
        cursor = conn.cursor()

        insert_sql = """
            INSERT IGNORE INTO push_notification_bank (
                content_uid, title, body, category_id, category_name,
                language, target_screen, status, scheduled_slot, attempt_count
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """

        total_inserted = 0
        total_skipped = 0

        for i in range(0, len(self.drafts), batch_size):
            chunk = self.drafts[i:i + batch_size]
            rows = [
                (
                    d["content_uid"],
                    d["title"],
                    d["body"],
                    d["category_id"],
                    d["category_name"],
                    d["language"],
                    d["target_screen"],
                    d["status"],
                    d["scheduled_slot"],
                    0
                )
                for d in chunk
            ]
            cursor.executemany(insert_sql, rows)
            conn.commit()
            total_inserted += cursor.rowcount
            total_skipped += (len(rows) - cursor.rowcount)
            print(f"    Synced batch {i // batch_size + 1}: {cursor.rowcount} inserted.")

        conn.close()
        print(f"[OK] Database sync completed: {total_inserted} inserted, {total_skipped} duplicates skipped.")
        return total_inserted, total_skipped


def main():
    parser = argparse.ArgumentParser(description="Postermaker 2,000 Push Notification Bank Generator")
    parser.add_argument("--target", type=int, default=2000, help="Target count of drafts (default: 2000)")
    parser.add_argument("--resume", action="store_true", help="Resume from checkpoint file")
    parser.add_argument("--sync-db", action="store_true", help="Directly sync to MySQL database")
    parser.add_argument("--export-sql", action="store_true", help="Export to SQL dump file")
    parser.add_argument("--export-json", action="store_true", help="Export to JSON file")
    parser.add_argument("--api-key", type=str, default=None, help="Optional AI API key")
    args = parser.parse_args()

    # If starting fresh without --resume, clear old checkpoint
    if not args.resume and os.path.exists(CHECKPOINT_FILE):
        try:
            os.remove(CHECKPOINT_FILE)
        except OSError:
            pass

    generator = NotificationGenerator(target_count=args.target, api_key=args.api_key)

    if args.resume:
        generator.load_checkpoint()

    generator.generate_all()
    generator.save_checkpoint()
    generator.export_sql()
    generator.export_json()

    if args.sync_db:
        generator.sync_to_database()


if __name__ == "__main__":
    main()
