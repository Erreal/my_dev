import type { Metadata } from "next";
import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getSiteData, getTechnologiesByCategory } from "@/lib/data";
import { getDictionary } from "@/lib/i18n";
import { ProjectFilter } from "@/components/portfolio/ProjectFilter";
import { FadeIn } from "@/components/shared/FadeIn";
import { JsonLd } from "@/components/shared/JsonLd";

export async function generateStaticParams() {
  return locales.map((locale) => ({ lang: locale }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ lang: string }>;
}): Promise<Metadata> {
  const { lang } = await params;
  const locale = lang as Locale;

  const title = locale === "ru" ? "Портфолио" : "Portfolio";
  const description =
    locale === "ru"
      ? "Проекты, над которыми я работал — от крупных GIS-приложений до интернет-магазинов и лендингов."
      : "Projects I've worked on — from large GIS applications to e-commerce stores and landing pages.";

  return {
    title,
    description,
    alternates: {
      languages: {
        "ru": "/ru/portfolio",
        "en": "/en/portfolio",
      },
    },
    openGraph: {
      title: `${title} | Mikhail Zakharov`,
      description,
      url: `https://erreality.ru/${locale}/portfolio`,
      locale: locale === "ru" ? "ru_RU" : "en_US",
    },
  };
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
      {/* JSON-LD ItemList Schema */}
      <JsonLd
        schema={{
          "@context": "https://schema.org",
          "@type": "ItemList",
          name: locale === "ru" ? "Портфолио" : "Portfolio",
          description:
            locale === "ru"
              ? "Проекты, над которыми я работал"
              : "Projects I've worked on",
          url: `https://erreality.ru/${locale}/portfolio`,
          numberOfItems: projects.length,
          itemListElement: projects.map((project, index) => ({
            "@type": "ListItem",
            position: index + 1,
            url: `https://erreality.ru/${locale}/portfolio/${
              locale === "ru" ? project.slug_ru : project.slug_en
            }`,
          })),
        }}
      />

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