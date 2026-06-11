import Link from "next/link";
import type { Project, Locale } from "@/lib/types";
import { getLocalizedField } from "@/lib/i18n";
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { ArrowRight } from "lucide-react";

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
        {projects.map((project) => {
          const title = getLocalizedField(project.title_ru, project.title_en, locale);
          const description = getLocalizedField(
            project.short_description_ru,
            project.short_description_en,
            locale
          );
          const slug = locale === "ru" ? project.slug_ru : project.slug_en;

          return (
            <Link key={project.id} href={`/${locale}/portfolio/${slug}`}>
              <Card className="group h-full overflow-hidden transition-all hover:shadow-lg hover:-translate-y-1">
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
                  <h3 className="font-semibold text-lg mb-2 group-hover:text-primary transition-colors">
                    {title}
                  </h3>
                  <p className="text-sm text-muted-foreground line-clamp-2">
                    {description}
                  </p>
                </CardContent>

                <CardFooter className="px-5 pb-5 pt-0 flex flex-wrap gap-1.5">
                  {project.technologies.slice(0, 4).map((tech) => (
                    <Badge key={tech.id} variant="secondary" className="text-xs">
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
    </section>
  );
}