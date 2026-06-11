import type { Technology } from "@/lib/types";
import { Badge } from "@/components/ui/badge";

interface TechStackSectionProps {
  techByCategory: Record<string, Technology[]>;
  title: string;
}

const categoryLabels: Record<string, string> = {
  frontend: "Frontend",
  backend: "Backend",
  tools: "Tools",
  other: "Other",
};

export function TechStackSection({ techByCategory, title }: TechStackSectionProps) {
  return (
    <section className="py-24">
      <h2 className="text-3xl font-bold tracking-tight mb-12">{title}</h2>
      <div className="grid gap-12 md:grid-cols-3">
        {Object.entries(techByCategory).map(([category, techs]) => (
          <div key={category}>
            <h3 className="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">
              {categoryLabels[category] || category}
            </h3>
            <div className="flex flex-wrap gap-2">
              {techs.map((tech) => (
                <Badge
                  key={tech.id}
                  variant="outline"
                  className="rounded-full px-3 py-1 text-sm"
                >
                  {tech.name}
                </Badge>
              ))}
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}