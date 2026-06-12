import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getProjectBySlug, getSiteData } from "@/lib/data";
import { getDictionary, getLocalizedField } from "@/lib/i18n";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { ArrowLeft, ExternalLink } from "lucide-react";
import { ScreenshotGallery } from "@/components/portfolio/ScreenshotGallery";
import { FadeIn } from "@/components/shared/FadeIn";

export async function generateStaticParams() {
  const { projects } = getSiteData();
  const params: { lang: string; slug: string }[] = [];

  for (const locale of locales) {
    for (const project of projects) {
      const slug = locale === "ru" ? project.slug_ru : project.slug_en;
      params.push({ lang: locale, slug });
    }
  }

  return params;
}

export default async function ProjectDetailPage({
  params,
}: {
  params: Promise<{ lang: string; slug: string }>;
}) {
  const { lang, slug } = await params;
  const locale = lang as Locale;
  const dict = getDictionary(locale);
  const project = getProjectBySlug(slug, locale);

  if (!project) {
    notFound();
  }

  const title = getLocalizedField(project.title_ru, project.title_en, locale);
  const fullDescription = getLocalizedField(
    project.full_description_ru,
    project.full_description_en,
    locale
  );
  const role = getLocalizedField(project.role_ru, project.role_en, locale);
  const responsibilities = getLocalizedField(
    project.responsibilities_ru,
    project.responsibilities_en,
    locale
  );

  const statusLabels: Record<string, { ru: string; en: string }> = {
    active: { ru: "Активен", en: "Active" },
    archived: { ru: "В архиве", en: "Archived" },
    completed: { ru: "Завершён", en: "Completed" },
  };

  return (
    <div className="mx-auto max-w-4xl px-6 py-24">
      {/* Back link */}
      <FadeIn direction="up">
        <Link
          href={`/${locale}/portfolio`}
          className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors mb-12"
        >
          <ArrowLeft className="h-4 w-4" />
          {dict.portfolio.back_to_portfolio}
        </Link>
      </FadeIn>

      {/* Header */}
      <FadeIn direction="up" delay={100}>
        <div className="mb-12">
          <h1 className="text-4xl font-bold tracking-tight mb-4">{title}</h1>

          <div className="flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
            {role && (
              <div>
                <span className="font-medium text-foreground">
                  {dict.portfolio.role}:
                </span>{" "}
                {role}
              </div>
            )}
            <div>
              <span className="font-medium text-foreground">
                {dict.portfolio.status}:
              </span>{" "}
              {statusLabels[project.status]?.[locale] ?? project.status}
            </div>
          </div>
        </div>
      </FadeIn>

      {/* Screenshots gallery with Lightbox */}
      <FadeIn direction="up" delay={200}>
        <ScreenshotGallery
          screenshots={project.screenshots}
          locale={locale}
          projectTitle={title}
        />
      </FadeIn>

      {/* Full description */}
      {fullDescription && (
        <FadeIn direction="up" delay={300}>
          <div className="mb-12">
            <p className="text-base leading-relaxed text-muted-foreground">
              {fullDescription}
            </p>
          </div>
        </FadeIn>
      )}

      {/* Responsibilities */}
      {responsibilities && (
        <FadeIn direction="up" delay={400}>
          <div className="mb-12">
            <h2 className="text-lg font-semibold mb-3">
              {dict.portfolio.responsibilities}
            </h2>
            <p className="text-sm leading-relaxed text-muted-foreground">
              {responsibilities}
            </p>
          </div>
        </FadeIn>
      )}

      {/* Technologies */}
      <FadeIn direction="up" delay={500}>
        <div className="mb-12">
          <h2 className="text-lg font-semibold mb-3">
            {dict.portfolio.technologies}
          </h2>
          <div className="flex flex-wrap gap-2">
            {project.technologies.map((tech) => (
              <Badge key={tech.id} variant="secondary" className="rounded-full px-3 py-1">
                {tech.name}
              </Badge>
            ))}
          </div>
        </div>
      </FadeIn>

      {/* External link */}
      {project.external_url && (
        <FadeIn direction="up" delay={600}>
          <a
            href={project.external_url}
            target="_blank"
            rel="noopener noreferrer"
          >
            <Button variant="outline" className="gap-2 rounded-full">
              {dict.portfolio.external_url}
              <ExternalLink className="h-4 w-4" />
            </Button>
          </a>
        </FadeIn>
      )}
    </div>
  );
}