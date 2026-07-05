"use client";

import { useState, useMemo } from "react";
import type { Project, Technology, Locale } from "@/lib/types";
import { ProjectCard } from "@/components/portfolio/ProjectCard";

interface ProjectFilterProps {
  projects: Project[];
  technologies: Technology[];
  locale: Locale;
  allLabel: string;
}

export function ProjectFilter({
  projects,
  technologies,
  locale,
  allLabel,
}: ProjectFilterProps) {
  const [activeTechId, setActiveTechId] = useState<number | null>(null);

  const filteredProjects = useMemo(() => {
    if (activeTechId === null) return projects;
    return projects.filter((project) =>
      project.technologies.some((tech) => tech.id === activeTechId)
    );
  }, [projects, activeTechId]);

  return (
    <div>
      {/* Filter chips */}
      <div className="mb-10 flex flex-wrap gap-2">
        <button
          onClick={() => setActiveTechId(null)}
          className={`rounded-full px-4 py-1.5 text-sm font-medium transition-colors ${
            activeTechId === null
              ? "bg-foreground text-background"
              : "bg-muted text-muted-foreground hover:bg-secondary hover:text-foreground"
          }`}
        >
          {allLabel}
        </button>
        {technologies.map((tech) => (
          <button
            key={tech.id}
            onClick={() =>
              setActiveTechId(activeTechId === tech.id ? null : tech.id)
            }
            className={`rounded-full px-4 py-1.5 text-sm font-medium transition-colors ${
              activeTechId === tech.id
                ? "bg-foreground text-background"
                : "bg-muted text-muted-foreground hover:bg-secondary hover:text-foreground"
            }`}
          >
            {tech.name}
          </button>
        ))}
      </div>

      {/* Project grid */}
      <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        {filteredProjects.map((project) => (
          <ProjectCard key={project.id} project={project} locale={locale} />
        ))}
      </div>

      {/* Empty state */}
      {filteredProjects.length === 0 && (
        <div className="py-24 text-center">
          <p className="text-muted-foreground">
            {locale === "ru"
              ? "Нет проектов с выбранной технологией"
              : "No projects found with the selected technology"}
          </p>
        </div>
      )}
    </div>
  );
}