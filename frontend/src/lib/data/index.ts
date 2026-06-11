import type { Profile, Technology, Experience, Project, SiteData } from "@/lib/types";
import profileData from "./profile.json";
import technologiesData from "./technologies.json";
import experienceData from "./experience.json";
import projectsData from "./projects.json";

const profile = profileData as unknown as Profile;
const technologies = technologiesData as unknown as Technology[];
const experience = experienceData as unknown as Experience[];
const rawProjects = projectsData as unknown as Array<Omit<Project, "technologies"> & { technologies: number[] }>;

// Resolve technology IDs to full Technology objects
function resolveTechnologies(techIds: number[]): Technology[] {
  return techIds
    .map((id) => technologies.find((t) => t.id === id))
    .filter((t): t is Technology => t !== undefined);
}

const projects: Project[] = rawProjects.map((p) => ({
  ...p,
  technologies: resolveTechnologies(p.technologies),
}));

export function getSiteData(): SiteData {
  return {
    profile,
    technologies,
    experience,
    projects,
  };
}

export function getProjectBySlug(slug: string, locale: "ru" | "en"): Project | undefined {
  return projects.find(
    (p) => (locale === "ru" ? p.slug_ru : p.slug_en) === slug
  );
}

export function getFeaturedProjects(): Project[] {
  return projects.filter((p) => p.featured);
}

export function getTechnologiesByCategory(): Record<string, Technology[]> {
  const categories: Record<string, Technology[]> = {};
  for (const tech of technologies) {
    if (!categories[tech.category]) {
      categories[tech.category] = [];
    }
    categories[tech.category].push(tech);
  }
  return categories;
}