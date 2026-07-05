export interface Profile {
  name_ru: string;
  name_en: string;
  position_ru: string;
  position_en: string;
  summary_ru: string;
  summary_en: string;
  photo: string | null;
  email: string;
  github_url: string | null;
  linkedin_url: string | null;
  telegram_url: string | null;
  resume_file: string | null;
}

export interface Technology {
  id: number;
  name: string;
  category: "frontend" | "backend" | "tools" | "other";
  icon: string | null;
  sort_order: number;
}

export interface Experience {
  id: number;
  company_ru: string;
  company_en: string;
  position_ru: string;
  position_en: string;
  description_ru: string | null;
  description_en: string | null;
  start_date: string;
  end_date: string | null;
  sort_order: number;
}

export interface Project {
  id: number;
  slug_ru: string;
  slug_en: string;
  title_ru: string;
  title_en: string;
  short_description_ru: string | null;
  short_description_en: string | null;
  full_description_ru: string | null;
  full_description_en: string | null;
  role_ru: string | null;
  role_en: string | null;
  responsibilities_ru: string | null;
  responsibilities_en: string | null;
  external_url: string | null;
  featured: boolean;
  status: "active" | "archived" | "completed";
  sort_order: number;
  technologies: Technology[];
  screenshots: ProjectScreenshot[];
}

export interface ProjectScreenshot {
  id: number;
  project_id: number;
  filename: string;
  thumbnail: string;
  title_ru: string | null;
  title_en: string | null;
  sort_order: number;
}

export interface SiteData {
  profile: Profile;
  technologies: Technology[];
  experience: Experience[];
  projects: Project[];
}

export type Locale = "ru" | "en";

export interface Dictionary {
  navigation: {
    portfolio: string;
    experience: string;
    contact: string;
  };
  home: {
    view_portfolio: string;
    download_resume: string;
    focus_areas: string;
    tech_stack: string;
    featured_projects: string;
  };
  portfolio: {
    title: string;
    all: string;
    role: string;
    status: string;
    external_url: string;
    technologies: string;
    responsibilities: string;
    back_to_portfolio: string;
  };
  experience: {
    title: string;
    present: string;
  };
  contact: {
    title: string;
    get_in_touch: string;
  };
  common: {
    loading: string;
    error: string;
    not_found: string;
    theme_light: string;
    theme_dark: string;
    language: string;
  };
}