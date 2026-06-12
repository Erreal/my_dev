import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getSiteData } from "@/lib/data";
import { getDictionary, getLocalizedField, formatDate } from "@/lib/i18n";
import { Badge } from "@/components/ui/badge";
import { FadeIn } from "@/components/shared/FadeIn";

export async function generateStaticParams() {
  return locales.map((locale) => ({ lang: locale }));
}

export default async function ExperiencePage({
  params,
}: {
  params: Promise<{ lang: string }>;
}) {
  const { lang } = await params;
  const locale = lang as Locale;
  const dict = getDictionary(locale);
  const { experience } = getSiteData();

  const sorted = [...experience].sort(
    (a, b) => a.sort_order - b.sort_order
  );

  return (
    <div className="mx-auto max-w-4xl px-6 py-24">
      {/* Header */}
      <FadeIn direction="up">
        <div className="mb-16">
          <h1 className="text-4xl font-bold tracking-tight mb-4">
            {dict.experience.title}
          </h1>
          <p className="text-lg text-muted-foreground max-w-2xl">
            {locale === "ru"
              ? "Мой профессиональный путь — от фриланса до Senior Frontend Developer в международных компаниях."
              : "My professional journey — from freelancing to Senior Frontend Developer at international companies."}
          </p>
        </div>
      </FadeIn>

      {/* Timeline */}
      <div className="relative">
        {/* Vertical line */}
        <div className="absolute left-[23px] top-2 bottom-2 w-px bg-border" />

        <div className="space-y-16">
          {sorted.map((exp, index) => {
            const company = getLocalizedField(
              exp.company_ru,
              exp.company_en,
              locale
            );
            const position = getLocalizedField(
              exp.position_ru,
              exp.position_en,
              locale
            );
            const description = getLocalizedField(
              exp.description_ru,
              exp.description_en,
              locale
            );
            const startDate = formatDate(exp.start_date, locale);
            const endDate = exp.end_date
              ? formatDate(exp.end_date, locale)
              : dict.experience.present;

            return (
              <FadeIn key={exp.id} direction="up" delay={index * 100}>
                <div className="relative pl-14">
                  {/* Timeline dot */}
                  <div className="absolute left-0 top-1.5 flex items-center justify-center">
                    <div className="h-[46px] w-[46px] rounded-full border-2 border-primary bg-background flex items-center justify-center">
                      <div className="h-3 w-3 rounded-full bg-primary" />
                    </div>
                  </div>

                  {/* Content */}
                  <div className="space-y-4">
                    <div className="space-y-1">
                      <h2 className="text-2xl font-semibold">{company}</h2>
                      <p className="text-base text-muted-foreground">
                        {position}
                      </p>
                      <Badge
                        variant="secondary"
                        className="rounded-full text-xs font-normal"
                      >
                        {startDate} — {endDate}
                      </Badge>
                    </div>

                    {description && (
                      <p className="text-sm leading-relaxed text-muted-foreground max-w-2xl">
                        {description}
                      </p>
                    )}
                  </div>
                </div>
              </FadeIn>
            );
          })}
        </div>
      </div>
    </div>
  );
}