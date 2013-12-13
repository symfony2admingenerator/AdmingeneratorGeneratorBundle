<?php

namespace Admingenerator\GeneratorBundle\Pagerfanta\View;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\ViewInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AdmingeneratorView implements ViewInterface
{

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function render(PagerfantaInterface $pagerfanta, $routeGenerator, array $options = array())
    {
        $options = array_merge(array(
            'proximity'          => 2,
            'previous_message'   => $this->translator->trans('pagerfanta.previous', array(), 'Admingenerator'),
            'next_message'       => $this->translator->trans('pagerfanta.next', array(), 'Admingenerator'),
            'css_disabled_class' => 'disabled',
            'css_dots_class'     => 'dots',
            'css_current_class'  => 'active',
        ), $options);

        $currentPage = $pagerfanta->getCurrentPage();

        $startPage = $currentPage - $options['proximity'];
        $endPage = $currentPage + $options['proximity'];

        if ($startPage < 1) {
            $endPage = min($endPage + (1 - $startPage), $pagerfanta->getNbPages());
            $startPage = 1;
        }
        if ($endPage > $pagerfanta->getNbPages()) {
            $startPage = max($startPage - ($endPage - $pagerfanta->getNbPages()), 1);
            $endPage = $pagerfanta->getNbPages();
        }

        $pages = array();

        // previous
        if ($pagerfanta->hasPreviousPage()) {
            $pages[] = array($pagerfanta->getPreviousPage(), $options['previous_message']);
        } else {
          //  $pages[] = sprintf('<span class="%s">%s</span>', $options['css_disabled_class'], $options['previous_message']);
        }

        // first
        if ($startPage > 1) {
            $pages[] = array(1, 1);
            if (3 == $startPage) {
                $pages[] = array(2, 2);
            } elseif (2 != $startPage) {
                $pages[] = sprintf('<li class="%s"><span class="%s">...</span></li>', $options['css_disabled_class'], $options['css_dots_class']);
            }
        }

        // pages
        for ($page = $startPage; $page <= $endPage; $page++) {
            if ($page == $currentPage) {
                $pages[] = sprintf('<li class="%s"><a href="#" class="number">%s</a></li>', $options['css_current_class'], $page);
            } else {
                $pages[] = array($page, $page);
            }
        }

        // last
        if ($pagerfanta->getNbPages() > $endPage) {
            if ($pagerfanta->getNbPages() > ($endPage + 1)) {
                if ($pagerfanta->getNbPages() > ($endPage + 2)) {
                    $pages[] = sprintf('<li class="%s"><span class="%s">...</span></li>', $options['css_disabled_class'], $options['css_dots_class']);
                } else {
                    $pages[] = array($endPage + 1, $endPage + 1);
                }
            }

            $pages[] = array($pagerfanta->getNbPages(), $pagerfanta->getNbPages());
        }

        // next
        if ($pagerfanta->hasNextPage()) {
            $pages[] = array($pagerfanta->getNextPage(), $options['next_message']);
        } else {
           // $pages[] = sprintf('<span class="%s">%s</span>', $options['css_disabled_class'], $options['next_message']);
        }

        // process
        $pagesHtml = '';
        foreach ($pages as $page) {
            if (is_string($page)) {
                $pagesHtml .= $page;
            } else {
                if (is_string($page[1])) {
                    $pagesHtml .= '<li><a href="'.$routeGenerator($page[0]).'">'.$page[1].'</a></li>';
                } else {
                    $pagesHtml .= '<li><a href="'.$routeGenerator($page[0]).'" class="number">'.$page[1].'</a></li>';
                }
            }
        }

        return '<ul>'.$pagesHtml.'</ul>';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admingenerator';
    }
}
