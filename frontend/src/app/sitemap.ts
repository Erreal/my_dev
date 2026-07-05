import type { MetadataRoute } from "next";
import { getSiteData } from "@/lib/data";

export const dynamic = "force-static";

export default function sitemap(): MetadataRoute.Sitemap {
  const { projects } = getSiteData();
  const baseUrl = "https://erreality.ru";

  const staticPages = [
    {
      url: baseUrl,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 1,
    },
    {
      url: `${baseUrl}/ru`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 1,
    },
    {
      url: `${baseUrl}/en`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 1,
    },
    {
      url: `${baseUrl}/ru/portfolio`,
      lastModified: new Date(),
      changeFrequency: "weekly" as const,
      priority: 0.9,
    },
    {
      url: `${baseUrl}/en/portfolio`,
      lastModified: new Date(),
      changeFrequency: "weekly" as const,
      priority: 0.9,
    },
    {
      url: `${baseUrl}/ru/experience`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.8,
    },
    {
      url: `${baseUrl}/en/experience`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.8,
    },
    {
      url: `${baseUrl}/ru/contact`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.7,
    },
    {
      url: `${baseUrl}/en/contact`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.7,
    },
  ];

  // Add project detail pages
  const projectPages = projects.flatMap((project) => [
    {
      url: `${baseUrl}/ru/portfolio/${project.slug_ru}`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.6,
    },
    {
      url: `${baseUrl}/en/portfolio/${project.slug_en}`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.6,
    },
  ]);

  return [...staticPages, ...projectPages];
}