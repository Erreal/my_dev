import type { Experience, Locale } from "@/lib/types";
import { getLocalizedField, formatDate } from "@/lib/i18n";

interface ExperienceTimelineProps {
  experience: Experience[];
  locale: Locale;
  title: string;
  presentText: string;
}

export function ExperienceTimeline({
  experience,
  locale,
  title,
  presentText,
}: ExperienceTimelineProps) {
  const sorted = [...experience].sort((a, b) => a.sort_order - b.sort_order);

  return (
    <section className="py-24">
      <h2 className="text-3xl font-bold tracking-tight mb-12">{title}</h2>

      <div className="relative">
        {/* Vertical line */}
        <div className="absolute left-[19px] top-2 bottom-2 w-px bg-border" />

        <div className="space-y-12">
          {sorted.map((exp) => {
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
              : presentText;

            return (
              <div key={exp.id} className="relative pl-12">
                {/* Timeline dot */}
                <div className="absolute left-0 top-1.5 flex items-center justify-center">
                  <div className="h-10 w-10 rounded-full border-2 border-primary bg-background flex items-center justify-center">
                    <div className="h-3 w-3 rounded-full bg-primary" />
                  </div>
                </div>

                {/* Content card */}
                <div className="space-y-3">
                  <div className="space-y-1">
                    <h3 className="text-xl font-semibold">{company}</h3>
                    <p className="text-sm text-muted-foreground">{position}</p>
                    <p className="text-xs text-muted-foreground/60">
                      {startDate} — {endDate}
                    </p>
                  </div>

                  {description && (
                    <p className="text-sm leading-relaxed text-muted-foreground">
                      {description}
                    </p>
                  )}
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}