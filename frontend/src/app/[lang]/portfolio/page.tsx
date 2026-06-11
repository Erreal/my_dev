import Link from "next/link";
import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getSiteData, getTechnologiesByCategory } from "@/lib/data";
import { getDictionary, getLocalizedField } from "@/lib/i18n";
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";

export async function generateStaticParams() {
  return locales.map((locale) => ({ lang: locale }));
}

export default async function PortfolioPage({
  params,
}: {
  params: Promise<{ lang: string }>;
}) {
  const { lang } = await params;
  const locale = lang as Locale;
  const dict = getDictionary(locale);
  const { projects } = getSiteData();
  const techByCategory = getTechnologiesByCategory();

  // Collect all unique technologies across all projects
  const allTechnologies = Object.values(techByCategory).flat();

  return (
    <div className="mx-auto max-w-6xl px-6 py-24">
      {/* Header */}
      <div className="mb-16">
        <h1 className="text-4xl font-bold tracking-tight mb-4">
          {dict.portfolio.title}
        </h1>
        <p className="text-lg text-muted-foreground max-w-2xl">
          {locale === "ru"
            ? "Проекты, над которыми я работал — от крупных GIS-приложений до интернет-магазинов и лендингов."
            : "Projects I've worked on — from large GIS applications to e-commerce stores and landing pages."}
        </p>
      </div>

      {/* Project Grid */}
      <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        {projects.map((project) => {
          const title = getLocalizedField(
            project.title_ru,
            project.title_en,
            locale
          );
          const description = getLocalizedField(
            project.short_description_ru,
            project.short_description_en,
            locale
          );
          const slug =
            locale === "ru" ? project.slug_ru : project.slug_en;

          return (
            <Link
              key={project.id}
              href={`/${locale}/portfolio/${slug}`}
              className="group"
            >
              <Card className="h-full overflow-hidden transition-all hover:shadow-lg hover:-translate-y-1">
                {/* Preview image placeholder */}
                <div className="aspect-video bg-muted flex items-center justify-center overflow-hidden">
                  {project.screenshots && project.screenshots.length > 0 ? (
                    <img
                      src={`/screenshots/${project.screenshots[0].thumbnail}`}
                      alt={title}
                      className="h-full w-full object-cover transition-transform group-hover:scale-105"
                    />
                  ) : (
                    <div className="text-4xl font-bold text-muted-foreground/20">
                      {title.charAt(0)}
                    </div>
                  )}
                </div>

                <CardContent className="p-5">
                  <h2 className="font-semibold text-lg mb-2 group-hover:text-primary transition-colors">
                    {title}
                  </h2>
                  <p className="text-sm text-muted-foreground line-clamp-2">
                    {description}
                  </p>
                </CardContent>

                <CardFooter className="px-5 pb-5 pt-0 flex flex-wrap gap-1.5">
                  {project.technologies.slice(0, 4).map((tech) => (
                    <Badge
                      key={tech.id}
                      variant="secondary"
                      className="text-xs"
                    >
                      {tech.name}
                    </Badge>
                  ))}
                  {project.technologies.length > 4 && (
                    <Badge variant="outline" className="text-xs">
                      +{project.technologies.length - 4}
                    </Badge>
                  )}
                </CardFooter>
              </Card>
            </Link>
          );
        })}
      </div>
    </div>
  );
}