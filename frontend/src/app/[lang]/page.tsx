import type { Metadata } from "next";
import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { getSiteData, getFeaturedProjects, getTechnologiesByCategory } from "@/lib/data";
import { getDictionary, getLocalizedField } from "@/lib/i18n";
import { HeroSection } from "@/components/home/HeroSection";
import { TechStackSection } from "@/components/home/TechStackSection";
import { FeaturedProjects } from "@/components/home/FeaturedProjects";
import { ExperienceTimeline } from "@/components/experience/ExperienceTimeline";
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
  const { profile } = getSiteData();
  const name = getLocalizedField(profile.name_ru, profile.name_en, locale);
  const position = getLocalizedField(profile.position_ru, profile.position_en, locale);
  const summary = getLocalizedField(profile.summary_ru, profile.summary_en, locale);

  const title = `${name} — ${position}`;
  const description = summary;

  return {
    title,
    description,
    alternates: {
      languages: {
        "ru": "/ru",
        "en": "/en",
      },
    },
    openGraph: {
      title,
      description,
      url: `https://erreality.ru/${locale}`,
      locale: locale === "ru" ? "ru_RU" : "en_US",
    },
  };
}

export default async function HomePage({
  params,
}: {
  params: Promise<{ lang: string }>;
}) {
  const { lang } = await params;
  const locale = lang as Locale;
  const dict = getDictionary(locale);
  const { profile, experience } = getSiteData();
  const featuredProjects = getFeaturedProjects();
  const techByCategory = getTechnologiesByCategory();

  const name = getLocalizedField(profile.name_ru, profile.name_en, locale);
  const position = getLocalizedField(profile.position_ru, profile.position_en, locale);
  const summary = getLocalizedField(profile.summary_ru, profile.summary_en, locale);

  return (
    <div className="mx-auto max-w-6xl px-6">
      {/* JSON-LD Person Schema */}
      <JsonLd
        schema={{
          "@context": "https://schema.org",
          "@type": "Person",
          name,
          givenName: locale === "ru" ? "Михаил" : "Mikhail",
          familyName: locale === "ru" ? "Захаров" : "Zakharov",
          jobTitle: position,
          description: summary,
          email: profile.email,
          url: "https://erreality.ru",
          sameAs: [
            profile.github_url,
            profile.linkedin_url,
          ].filter(Boolean),
          knowsAbout: [
            "React",
            "TypeScript",
            "JavaScript",
            "Frontend Development",
            "Web Development",
            "GIS",
            "UI/UX",
          ],
        }}
      />

      {/* Hero Section */}
      <HeroSection
        name={name}
        position={position}
        summary={summary}
        githubUrl={profile.github_url}
        linkedinUrl={profile.linkedin_url}
        telegramUrl={profile.telegram_url}
        email={profile.email}
        resumeFile={profile.resume_file}
        locale={locale}
        dict={dict}
      />

      {/* Technology Stack */}
      <FadeIn direction="up" delay={100}>
        <TechStackSection
          techByCategory={techByCategory}
          title={dict.home.tech_stack}
        />
      </FadeIn>

      {/* Featured Projects */}
      <FadeIn direction="up" delay={200}>
        <FeaturedProjects
          projects={featuredProjects}
          locale={locale}
          title={dict.home.featured_projects}
          viewAllText={dict.navigation.portfolio}
        />
      </FadeIn>

      {/* Experience Timeline */}
      <FadeIn direction="up" delay={300}>
        <ExperienceTimeline
          experience={experience}
          locale={locale}
          title={dict.experience.title}
          presentText={dict.experience.present}
        />
      </FadeIn>
    </div>
  );
}