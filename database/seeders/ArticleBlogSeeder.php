<?php

namespace Database\Seeders;

use App\Models\ArticleBlog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Six articles sur le site internet commercial, écrits à partir du texte
 * « D'abord, comprendre le rôle du site internet » + « Le parcours de conversion ».
 * Idempotent (clé : slug). Les liens internes sont relatifs (valables en local et en ligne).
 */
class ArticleBlogSeeder extends Seeder
{
    public function run(): void
    {
        $cta = '<p><strong>Votre site guide-t-il vraiment vos visiteurs jusqu’à l’action ?</strong> Décrivez votre activité en cinq questions : je vous réponds avec une proposition adaptée. <a href="/demande-de-projet">Démarrer mon projet</a>.</p>';

        $articles = [
            [
                'titre' => 'Votre site internet a un seul travail : faire agir vos visiteurs',
                'date' => '2026-11-03 09:00:00',
                'extrait' => 'Un site commercial n’est pas une vitrine qu’on admire. C’est un guide qui accompagne chaque visiteur vers une action précise : commander, demander un devis, prendre rendez-vous.',
                'contenu' => <<<'HTML'
<p>Beaucoup d’entrepreneurs voient leur site comme une carte de visite en ligne : on y met un logo, quelques photos, une page « À propos », et l’on attend que les clients arrivent. Puis rien ne se passe. Le site existe, mais il ne travaille pas.</p>
<p>Un site internet commercial a pourtant une mission très simple à énoncer : <strong>guider le visiteur vers une action précise</strong>. Tout le reste, le design, les textes, les images, n’est qu’un moyen au service de cette mission.</p>
<h2>Chaque visiteur arrive avec une intention</h2>
<p>Personne ne visite votre site par hasard. Lorsqu’une personne arrive sur votre page, elle a généralement une idée en tête :</p>
<ul>
<li>découvrir votre entreprise ;</li>
<li>connaître vos produits ou vos services ;</li>
<li>connaître vos prix ;</li>
<li>vérifier que vous êtes fiable ;</li>
<li>commander quelque chose ;</li>
<li>demander un devis ;</li>
<li>prendre rendez-vous.</li>
</ul>
<p>Le travail du site consiste à reconnaître cette intention et à accompagner la personne jusqu’à l’action qui correspond à son besoin. Ni plus, ni moins.</p>
<h2>Si le visiteur doit chercher, il part</h2>
<p>Prenons un exemple très simple. Un visiteur arrive sur le site d’un restaurant. Il a faim, il veut commander. Il ne doit pas avoir à chercher pendant dix minutes comment faire. Le site doit lui montrer les plats, lui permettre de choisir, de préciser sa commande et de la transmettre au restaurant.</p>
<p>Si le bouton de commande est caché au bas d’une page, si le menu n’affiche pas les prix, si le numéro de téléphone est introuvable, ce visiteur ne vous en voudra pas : il ira simplement commander ailleurs. Sur internet, la concurrence est à un clic.</p>
<h2>Trois questions pour juger votre site</h2>
<p>Avant de parler de couleurs ou de polices, posez-vous ces trois questions :</p>
<ol>
<li><strong>Quelle est l’action principale</strong> que j’attends de mes visiteurs ? Une seule, clairement nommée.</li>
<li><strong>Combien de temps</strong> faut-il à un nouveau visiteur pour trouver le bouton qui y mène ?</li>
<li><strong>Qu’est-ce qui pourrait le faire hésiter</strong> juste avant de cliquer : le prix, la confiance, la livraison, le délai ?</li>
</ol>
<p>Si vous ne savez pas répondre à la première question, votre site ne le sait pas non plus. Et un site qui ne sait pas ce qu’il veut obtenir n’obtient rien.</p>
<blockquote>Un beau site que personne n’utilise reste un beau site. Un site clair que vos clients utilisent devient un outil de vente.</blockquote>
<h2>C’est cette logique qui guide toute la conception</h2>
<p>Quand on part de l’action attendue, chaque décision devient plus simple. Quelle information placer en premier ? Celle qui aide le visiteur à avancer. Quel texte écrire sur le bouton ? Celui qui décrit exactement ce qui va se passer. Quelle page supprimer ? Celle qui détourne de l’objectif.</p>
<p>Votre site n’a qu’un seul travail : faire agir vos visiteurs. Tout ce qui l’aide à faire ce travail mérite sa place. Tout le reste peut attendre.</p>
HTML,
            ],
            [
                'titre' => 'Les 7 étapes qui transforment un visiteur en client',
                'date' => '2026-11-06 09:00:00',
                'extrait' => 'Découverte, compréhension, réassurance, passage à l’action, collecte, transmission, suivi : voici le parcours que tout site commercial doit organiser, étape par étape.',
                'contenu' => <<<'HTML'
<p>Un site qui vend ne se contente pas de recevoir des visiteurs. Il <strong>organise les étapes</strong> qui permettent de transformer une simple visite en opportunité commerciale. Quand l’une de ces étapes manque, le visiteur décroche, souvent sans que vous sachiez pourquoi.</p>
<p>Voici le parcours de conversion, tel que je le construis pour chaque projet.</p>
<h2>1. La découverte</h2>
<p>Le visiteur arrive depuis Facebook, Google, WhatsApp, TikTok ou une publicité. Il ne vous connaît pas encore. Dans les premières secondes, il doit comprendre qu’il est au bon endroit : un titre clair, qui dit ce que vous faites et pour qui.</p>
<h2>2. La compréhension</h2>
<p>Il découvre votre entreprise, votre activité, vos produits ou vos services. C’est ici que la clarté fait toute la différence : des offres présentées simplement, des prix visibles quand c’est possible, des mots que votre client utilise lui-même.</p>
<h2>3. La réassurance</h2>
<p>Avant de s’engager, il vérifie que vous êtes fiable. Il consulte vos réalisations, les avis de vos clients, vos garanties, vos références et les informations utiles : adresse, délais, moyens de paiement. Sans ces preuves, l’intérêt reste une simple curiosité.</p>
<h2>4. Le passage à l’action</h2>
<p>Il clique sur un bouton : commander, demander un devis, réserver, prendre rendez-vous. Ce bouton doit être visible, répété aux bons endroits et formulé avec précision. « Envoyer » ne dit rien ; « Recevoir mon devis » dit tout.</p>
<h2>5. La collecte des informations</h2>
<p>Il remplit un formulaire, sélectionne des produits ou décrit son besoin. C’est l’étape la plus fragile : chaque champ inutile est une raison de plus d’abandonner. On ne demande que ce qui sert vraiment à répondre.</p>
<h2>6. La transmission de la demande</h2>
<p>Vous recevez les informations, complètes et lisibles, au bon endroit : par e-mail, dans un tableau de bord, sur WhatsApp. Une demande qui se perd dans une boîte mail mal configurée est un client perdu.</p>
<h2>7. Le suivi et la conversion</h2>
<p>Vous répondez, vous échangez avec le prospect, vous confirmez la commande ou vous établissez le devis. Le site a fait sa part : il vous a apporté une personne intéressée, avec son besoin déjà décrit.</p>
<blockquote>Le site ne vend pas à votre place. Il prépare la vente, pour que votre échange avec le client commence au bon endroit.</blockquote>
<h2>Où votre parcours se casse-t-il ?</h2>
<p>Reprenez ces sept étapes et regardez votre site avec les yeux d’un inconnu. Le titre est-il clair ? Vos offres sont-elles compréhensibles ? Vos preuves sont-elles visibles ? Le bouton est-il évident ? Le formulaire est-il court ? Recevez-vous bien les demandes ? Les traitez-vous rapidement ?</p>
<p>Il suffit souvent de réparer une seule étape pour que tout le parcours se remette à fonctionner.</p>
HTML,
            ],
            [
                'titre' => 'Pourquoi un beau site ne suffit pas à vendre',
                'date' => '2026-11-09 09:00:00',
                'extrait' => 'Un design parfait avec un message vide ne convertit personne. Ce qui fait vendre un site, c’est d’abord la clarté de ce qu’il dit et de ce qu’il demande au visiteur.',
                'contenu' => <<<'HTML'
<p>Vous avez sans doute déjà vu ce genre de site : de belles photos, des animations élégantes, des couleurs soignées… et au bout de deux minutes, une question qui reste sans réponse : <em>mais qu’est-ce qu’ils vendent, exactement ?</em></p>
<p>C’est l’erreur la plus fréquente que je rencontre. Des entrepreneurs investissent dans des sites magnifiques qui ne leur apportent presque aucun client. Le design est parfait. Le message est vide.</p>
<h2>Le visiteur ne vient pas admirer, il vient décider</h2>
<p>Quelqu’un qui arrive sur votre site cherche une réponse à son besoin : ce produit est-il pour moi ? Combien coûte-t-il ? Puis-je vous faire confiance ? Comment je commande ? Un beau décor ne répond à aucune de ces questions. Il les retarde.</p>
<p>Le design a un rôle essentiel : rendre l’information facile à lire et donner une impression de sérieux. Mais il vient <strong>après</strong> le message, jamais à sa place.</p>
<h2>Les signes d’un site « beau mais muet »</h2>
<ul>
<li>Le titre principal parle de vous (« Bienvenue chez… ») plutôt que de ce que le client y gagne.</li>
<li>Les offres sont décrites avec des mots vagues : « solutions », « excellence », « qualité ».</li>
<li>Les prix, les délais et les zones de livraison sont absents.</li>
<li>Il n’y a pas de bouton clair, ou il y en a dix qui mènent partout.</li>
<li>Le visiteur doit vous écrire pour obtenir une information qui aurait pu figurer sur la page.</li>
</ul>
<p>Si vous vous reconnaissez dans deux de ces points, votre site est probablement joli… et silencieux.</p>
<h2>Le message d’abord, la forme ensuite</h2>
<p>Quand je conçois un site, je commence par les mots : ce que vous faites, pour qui, ce que le client obtient, ce qu’il doit faire ensuite. Ce n’est qu’une fois ce message clair que la mise en page vient le servir : hiérarchie, contraste, espaces, boutons.</p>
<blockquote>Sans message clair, aucun site ne vend. Peu importe la qualité du code ou la beauté des images.</blockquote>
<h2>Un test simple</h2>
<p>Montrez votre page d’accueil pendant cinq secondes à quelqu’un qui ne vous connaît pas, puis cachez-la. Demandez-lui : « Que vend cette entreprise ? Et que dois-tu faire si cela t’intéresse ? » S’il hésite, ce n’est pas un problème de couleurs. C’est un problème de message.</p>
<p>La bonne nouvelle, c’est qu’un message se corrige plus vite qu’un design. Et c’est souvent lui qui fait toute la différence entre un site qu’on visite et un site qui vend.</p>
HTML,
            ],
            [
                'titre' => 'Restaurant, boutique, prestataire : à chaque activité son parcours',
                'date' => '2026-11-12 09:00:00',
                'extrait' => 'Commander un plat, choisir un produit, demander un devis, réserver un créneau : l’action change selon l’activité, et le site doit être construit autour d’elle.',
                'contenu' => <<<'HTML'
<p>Le principe ne change pas : un site commercial guide le visiteur vers une action. Mais cette action, elle, change selon votre activité. Et c’est elle qui doit dicter la structure de votre site. Regardons comment cela fonctionne concrètement.</p>
<h2>Le restaurant : commander sans chercher</h2>
<p>Le visiteur a faim et il est pressé. Le site doit lui montrer immédiatement les plats, avec leurs photos et leurs prix, lui permettre de choisir, de préciser sa commande (quantité, accompagnement, heure) et de la transmettre au restaurant en quelques gestes.</p>
<p><strong>Ce qui compte :</strong> le menu en premier, les prix visibles, un bouton « Commander » toujours accessible, les horaires et la zone de livraison sans avoir à les chercher.</p>
<h2>La boutique : choisir en confiance</h2>
<p>Le visiteur compare. Il veut voir le produit sous plusieurs angles, connaître sa taille, sa couleur, son prix, et savoir comment il sera livré. Beaucoup de boutiques vendent encore sur WhatsApp : le site peut alors préparer la commande et l’envoyer par message, déjà complète.</p>
<p><strong>Ce qui compte :</strong> des photos fidèles, des fiches produits claires, les frais et délais de livraison affichés, les moyens de paiement, et des avis de clients.</p>
<h2>Le prestataire de services : demander un devis</h2>
<p>Artisan, agence, consultant, entreprise de nettoyage ou de rénovation : le visiteur ne peut pas « acheter » directement, car chaque besoin est différent. L’action attendue est la demande de devis.</p>
<p><strong>Ce qui compte :</strong> des services expliqués simplement, des réalisations en images, un formulaire qui pose les bonnes questions (type de besoin, lieu, délai, budget) pour que vous puissiez répondre vite et précisément.</p>
<h2>Les métiers sur rendez-vous : réserver un créneau</h2>
<p>Coiffure, beauté, santé, coaching, formation : le visiteur veut savoir si vous êtes disponible et réserver sans échanger dix messages.</p>
<p><strong>Ce qui compte :</strong> les prestations et leurs durées, les tarifs, les créneaux disponibles ou un moyen simple de proposer une date, et une confirmation immédiate.</p>
<blockquote>Avant de dessiner un site, je pose toujours la même question : quelle action votre client doit-il pouvoir faire, et en combien de gestes ?</blockquote>
<h2>Une seule action principale par page</h2>
<p>Quelle que soit votre activité, gardez une règle : chaque page a une action principale. Les autres liens existent, mais ils restent secondaires. Un visiteur à qui l’on propose trop de choix finit souvent par n’en faire aucun.</p>
<p>Votre activité a son propre parcours. Votre site doit le suivre, pas l’inverse.</p>
HTML,
            ],
            [
                'titre' => 'Ce qui rassure un visiteur avant de passer à l’action',
                'date' => '2026-11-15 09:00:00',
                'extrait' => 'Entre l’intérêt et la commande, il y a une étape décisive : la confiance. Réalisations, avis, garanties, informations pratiques : voici ce qui fait passer à l’action.',
                'contenu' => <<<'HTML'
<p>Un visiteur peut être intéressé par votre offre et pourtant ne jamais passer à l’action. Entre l’envie et la commande, il y a une étape que beaucoup de sites oublient : <strong>la réassurance</strong>. Avant de vous confier son argent, son projet ou ses coordonnées, le visiteur veut être sûr de faire le bon choix.</p>
<h2>Les questions silencieuses du visiteur</h2>
<p>Il ne vous les posera pas, mais il se les pose :</p>
<ul>
<li>Cette entreprise existe-t-elle vraiment ?</li>
<li>D’autres personnes lui ont-elles fait confiance ?</li>
<li>Que se passe-t-il si je ne suis pas satisfait ?</li>
<li>Combien de temps vais-je attendre ?</li>
<li>Qui va me répondre ?</li>
</ul>
<p>Chaque question sans réponse est une raison de reporter la décision. Et sur internet, « plus tard » veut souvent dire « jamais ».</p>
<h2>Les réalisations : montrer plutôt que promettre</h2>
<p>Des photos de vos produits, de vos chantiers, de vos plats, de vos projets terminés. Une réalisation concrète vaut mieux que dix adjectifs. Si possible, ajoutez le contexte : pour qui, quel besoin, quel résultat.</p>
<h2>Les avis clients : la parole des autres</h2>
<p>Un avis sincère, signé d’un prénom et d’une activité, rassure bien plus qu’un slogan. Publiez uniquement de vrais retours, avec l’accord de vos clients : un avis inventé se repère, et il détruit la confiance qu’il devait créer.</p>
<h2>Les garanties et les engagements</h2>
<p>Délai de livraison, possibilité d’échange, premier échange gratuit, devis sans engagement : dites clairement ce que vous promettez. Un engagement précis réduit le risque perçu.</p>
<h2>Les informations utiles</h2>
<p>Adresse, horaires, zone de livraison, moyens de paiement, numéro de téléphone, WhatsApp. Ces détails paraissent évidents, mais leur absence fait naître le doute. Une entreprise qui se cache inquiète.</p>
<h2>Un visage</h2>
<p>Surtout pour un indépendant ou une petite entreprise : une photo, un prénom, quelques lignes sur qui vous êtes. On fait plus facilement confiance à une personne qu’à un logo.</p>
<blockquote>La réassurance ne se place pas tout en bas de la page. Elle accompagne le visiteur juste avant chaque bouton important.</blockquote>
<h2>Placez les preuves au bon moment</h2>
<p>Un avis client juste à côté du bouton « Commander », une garantie sous le formulaire, une réalisation à côté de la description du service : la preuve est plus efficace quand elle arrive au moment précis où le doute apparaît.</p>
HTML,
            ],
            [
                'titre' => 'Après le clic : formulaire, transmission et suivi',
                'date' => '2026-11-18 09:00:00',
                'extrait' => 'Le visiteur a cliqué sur « Commander » ou « Demander un devis ». Le plus délicat commence : recueillir les bonnes informations, les recevoir sans en perdre une, et répondre vite.',
                'contenu' => <<<'HTML'
<p>On imagine souvent que le travail du site s’arrête au moment où le visiteur clique sur le bouton. C’est en réalité là que commence la partie la plus délicate : <strong>transformer ce clic en échange réel</strong> avec un client. Trois étapes décident de la suite : la collecte, la transmission et le suivi.</p>
<h2>La collecte : un formulaire court et utile</h2>
<p>Le formulaire est l’endroit où l’on perd le plus de clients. Chaque champ demande un effort ; chaque question floue fait hésiter. Quelques règles simples :</p>
<ul>
<li>ne demandez que ce qui vous sert vraiment à répondre ;</li>
<li>posez les questions dans l’ordre où le client y pense ;</li>
<li>découpez un long formulaire en petites étapes, avec une barre de progression ;</li>
<li>expliquez pourquoi vous demandez une information sensible, comme le téléphone ;</li>
<li>affichez des messages d’erreur clairs, à côté du champ concerné.</li>
</ul>
<h2>La transmission : ne perdre aucune demande</h2>
<p>Une demande envoyée doit arriver, complète et lisible, au bon endroit. Elle est enregistrée sur le site, et vous recevez une notification par e-mail avec toutes les informations. Si l’envoi de l’e-mail échoue, la demande, elle, ne doit jamais disparaître.</p>
<p>Côté visiteur, une confirmation immédiate est indispensable : un message à l’écran, puis un e-mail qui récapitule sa demande. Sans cela, il se demande si tout a bien fonctionné… et parfois, il recommence chez un concurrent.</p>
<h2>Le suivi : la vitesse fait la différence</h2>
<p>Un prospect qui vient de remplir votre formulaire est au maximum de son intérêt. Chaque heure qui passe le refroidit. Répondez vite, même brièvement : « Merci, j’ai bien reçu votre demande, je vous envoie une proposition demain. » Ce simple message garde la conversation ouverte.</p>
<p>Ensuite vient votre travail : échanger, comprendre, confirmer la commande ou établir le devis. Le site vous a apporté une personne intéressée, avec son besoin déjà décrit. C’est un énorme gain de temps par rapport à une conversation qui part de zéro.</p>
<blockquote>Le site ne remplace pas votre réponse. Il fait en sorte que chaque demande arrive préparée, et qu’aucune ne se perde en route.</blockquote>
<h2>Votre site passe-t-il le test ?</h2>
<p>Faites une demande sur votre propre site, comme un client. Combien de champs avez-vous remplis ? Avez-vous reçu une confirmation ? La demande est-elle bien arrivée, complète ? Combien de temps auriez-vous attendu une réponse ? Les réponses à ces questions disent beaucoup de ce que vivent vos clients.</p>
HTML,
            ],
        ];

        $nbsp = fn (string $t) => str_replace([' :', ' ;', ' ?', ' !', '« ', ' »'], ["\u{00A0}:", "\u{00A0};", "\u{00A0}?", "\u{00A0}!", "«\u{00A0}", "\u{00A0}»"], $t);

        foreach ($articles as $a) {
            ArticleBlog::updateOrCreate(
                ['slug' => Str::slug($a['titre'])],
                [
                    'titre' => $a['titre'],
                    'extrait' => $nbsp($a['extrait']),
                    'contenu' => $nbsp($a['contenu'].$cta),
                    'statut' => 'Publié',
                    'date_publication' => $a['date'],
                ],
            );
        }
    }
}
