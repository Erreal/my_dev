import Link from "next/link";
import type { Project, Locale } from "@/lib/types";
import { Button } from "@/components/ui/button";
import { ArrowRight } from "lucide-react";
import { ProjectCard } from "@/components/portfolio/ProjectCard";

interface FeaturedProjectsProps {
  projects: Project[];
  locale: Locale;
  title: string;
  viewAllText: string;
}

export function FeaturedProjects({
  projects,
  locale,
  title,
  viewAllText,
}: FeaturedProjectsProps) {
  return (
    <section className="py-24">
      <div className="flex items-center justify-between mb-12">
        <h2 className="text-3xl font-bold tracking-tight">{title}</h2>
        <Link href={`/${locale}/portfolio`}>
          <Button variant="ghost" className="gap-2">
            {viewAllText}
            <ArrowRight className="h-4 w-4" />
          </Button>
        </Link>
      </div>

      <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        {projects.map((project) => (
          <ProjectCard key={project.id} project={project} locale={locale} />
        ))}
      </div>
    </section>
  );
}