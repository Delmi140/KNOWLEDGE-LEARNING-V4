<?php

namespace App\Controller\Admin;

use App\Entity\Lessons;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class LessonsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lessons::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            MoneyField::new('price')->setCurrency('EUR'),
            TextEditorField::new('content'),
            TextField::new('attachmentFile')->setFormType(VichImageType::class),
            ImageField::new('attachment')->setBasePath('/uploads/attachments')->onlyOnIndex(),
            DateField::new('createdAt'),
            AssociationField::new('cursus'),
        ];
    }
    
}
