import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getSiteData, getTechnologiesByCategory } from "@/lib/data";
import { getDictionary } from "@/lib/i18n";
import { ProjectFilter } from "@/components/portfolio/ProjectFilter";
import { FadeIn } from "@/components/shared/FadeIn";

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
      <FadeIn direction="up">
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
      </FadeIn>

      {/* Project Filter + Grid */}
      <FadeIn direction="up" delay={100}>
        <ProjectFilter
          projects={projects}
          technologies={allTechnologies}
          locale={locale}
          allLabel={dict.portfolio.all}
        />
      </FadeIn>
    </div>
  );
}