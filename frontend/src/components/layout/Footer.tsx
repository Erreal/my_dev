import type { Locale } from "@/lib/types";
import { getDictionary } from "@/lib/i18n";
import { SocialLinks } from "@/components/shared/SocialLinks";
import { getSiteData } from "@/lib/data";

interface FooterProps {
  locale: Locale;
}

export function Footer({ locale }: FooterProps) {
  const { profile } = getSiteData();
  const dict = getDictionary(locale);
  const currentYear = new Date().getFullYear();

  return (
    <footer className="border-t border-border/40">
      <div className="mx-auto max-w-6xl px-6 py-12">
        <div className="flex flex-col items-center gap-6 md:flex-row md:justify-between">
          <div className="flex flex-col items-center gap-2 md:items-start">
            <p className="text-sm text-muted-foreground">
              &copy; {currentYear} {locale === "ru" ? profile.name_ru : profile.name_en}
            </p>
          </div>

          <SocialLinks
            github={profile.github_url}
            linkedin={profile.linkedin_url}
            telegram={profile.telegram_url}
            email={profile.email}
            iconSize={18}
          />
        </div>
      </div>
    </footer>
  );
}