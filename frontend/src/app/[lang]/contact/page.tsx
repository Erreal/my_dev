import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getSiteData } from "@/lib/data";
import { getDictionary } from "@/lib/i18n";
import { SocialLinks } from "@/components/shared/SocialLinks";
import { Mail, MapPin } from "lucide-react";

export async function generateStaticParams() {
  return locales.map((locale) => ({ lang: locale }));
}

export default async function ContactPage({
  params,
}: {
  params: Promise<{ lang: string }>;
}) {
  const { lang } = await params;
  const locale = lang as Locale;
  const dict = getDictionary(locale);
  const { profile } = getSiteData();

  return (
    <div className="mx-auto max-w-4xl px-6 py-24">
      {/* Header */}
      <div className="mb-16">
        <h1 className="text-4xl font-bold tracking-tight mb-4">
          {dict.contact.title}
        </h1>
        <p className="text-lg text-muted-foreground max-w-2xl">
          {dict.contact.get_in_touch}
        </p>
      </div>

      <div className="grid gap-12 md:grid-cols-2">
        {/* Contact info */}
        <div className="space-y-8">
          {/* Email */}
          {profile.email && (
            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10">
                <Mail className="h-5 w-5 text-primary" />
              </div>
              <div>
                <h3 className="font-medium mb-1">
                  {locale === "ru" ? "Email" : "Email"}
                </h3>
                <a
                  href={`mailto:${profile.email}`}
                  className="text-sm text-muted-foreground hover:text-primary transition-colors"
                >
                  {profile.email}
                </a>
              </div>
            </div>
          )}

          {/* Location */}
          <div className="flex items-start gap-4">
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10">
              <MapPin className="h-5 w-5 text-primary" />
            </div>
            <div>
              <h3 className="font-medium mb-1">
                {locale === "ru" ? "Локация" : "Location"}
              </h3>
              <p className="text-sm text-muted-foreground">
                {locale === "ru"
                  ? "Москва, Россия"
                  : "Moscow, Russia"}
              </p>
            </div>
          </div>

          {/* Social links */}
          <div className="flex items-start gap-4">
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10">
              <SocialLinks
                github={profile.github_url}
                linkedin={profile.linkedin_url}
                telegram={profile.telegram_url}
                email={profile.email}
                iconSize={18}
              />
            </div>
            <div>
              <h3 className="font-medium mb-1">
                {locale === "ru" ? "Социальные сети" : "Social Networks"}
              </h3>
              <div className="flex gap-3">
                {profile.github_url && (
                  <a
                    href={profile.github_url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-sm text-muted-foreground hover:text-primary transition-colors"
                  >
                    GitHub
                  </a>
                )}
                {profile.linkedin_url && (
                  <a
                    href={profile.linkedin_url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-sm text-muted-foreground hover:text-primary transition-colors"
                  >
                    LinkedIn
                  </a>
                )}
                {profile.telegram_url && (
                  <a
                    href={profile.telegram_url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-sm text-muted-foreground hover:text-primary transition-colors"
                  >
                    Telegram
                  </a>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* Availability / CTA */}
        <div className="rounded-xl border bg-muted/30 p-8">
          <h2 className="text-xl font-semibold mb-4">
            {locale === "ru"
              ? "Открыт к предложениям"
              : "Open to Opportunities"}
          </h2>
          <p className="text-sm leading-relaxed text-muted-foreground mb-6">
            {locale === "ru"
              ? "Я всегда открыт к интересным проектам и предложениям о работе. Если у вас есть проект, который требует опытного фронтенд-разработчика, свяжитесь со мной любым удобным способом."
              : "I'm always open to interesting projects and job opportunities. If you have a project that needs an experienced frontend developer, feel free to reach out through any channel."}
          </p>
          {profile.email && (
            <a
              href={`mailto:${profile.email}`}
              className="inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-primary/80 transition-colors"
            >
              <Mail className="h-4 w-4" />
              {profile.email}
            </a>
          )}
        </div>
      </div>
    </div>
  );
}